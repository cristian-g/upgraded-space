<?php

namespace Pwbox\Controller\utils;

class RoleCalculator {
    public static function computeRole(&$folder, &$role, $container) {
        // Role
        $role = null;
        $share = null;

        $folderAuxI = $folder;
        while ($folderAuxI->getIdParent() != null) {
            $folderAuxI = ($container->get('get_upload_by_id_use_case'))($folderAuxI->getIdParent());
        }
        if ($folderAuxI->getIdUser() == $_SESSION["user_id"]) {
            $role = 'owner';
        }
        else {
            $folderAux = $folder;
            $share = ($container->get('get_share_by_upload_id_use_case'))($folderAux->getId(), $_SESSION["user_id"]);
            $roles = [];
            if ($share != null && $share->getIdUserDestination() == $_SESSION["user_id"]) {
                // Save role
                array_push($roles, $share->getRole());
            }
            while ($share == null || $share->getIdUserDestination() != $_SESSION["user_id"]) {
                if ($folderAux->getIdParent() == null) break;
                $folderAux = ($container->get('get_upload_by_id_use_case'))($folderAux->getIdParent());
                $share = ($container->get('get_share_by_upload_id_use_case'))($folderAux->getId(), $_SESSION["user_id"]);
                if ($share != null && $share->getIdUserDestination() == $_SESSION["user_id"]) {
                    // Save role
                    array_push($roles, $share->getRole());
                }
            }
            if ($share != null && $share->getIdUserDestination() == $_SESSION["user_id"]) {
                // Choose best role (not just $role = $share->getRole())
                $containsAdmin = in_array("admin", $roles);
                $containsReader = in_array("reader", $roles);
                if ($containsReader) {
                    $role = 'reader';
                }
                if ($containsAdmin) {
                    $role = 'admin';
                }
            }
            else {
                $folderAux2 = $folder;
                if ($folderAux2->getIdParent() != null) {
                    $folderAux2 = ($container->get('get_upload_by_id_use_case'))($folderAux2->getIdParent());
                    while ($folderAux2->getIdUser() != $_SESSION["user_id"]) {
                        if ($folderAux2->getIdParent() == null) break;
                        $folderAux2 = ($container->get('get_upload_by_id_use_case'))($folderAux2->getIdParent());
                    }
                    if ($folderAux2->getIdUser() == $_SESSION["user_id"]) {
                        $role = 'owner';
                    }
                }
            }
        }
        return $share;
    }
}