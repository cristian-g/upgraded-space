<?php

namespace Pwbox\Controller\utils;

class RoleCalculator {
    public static function computeRole(&$folder, &$role, $container) {
        // Role
        $role = null;
        $share = null;
        if ($folder->getIdUser() == $_SESSION["user_id"]) {
            $role = 'owner';
        }
        else {
            $folderAux = $folder;
            $share = ($container->get('get_share_by_upload_id_use_case'))($folderAux->getId(), $_SESSION["user_id"]);
            while ($share == null || $share->getIdUserDestination() != $_SESSION["user_id"]) {
                if ($folderAux->getIdParent() == null) break;
                $folderAux = ($container->get('get_folder_by_id_use_case'))($folderAux->getIdParent());
                $share = ($container->get('get_share_by_upload_id_use_case'))($folderAux->getId(), $_SESSION["user_id"]);
                if ($share != null && $share->getIdUserDestination() == $_SESSION["user_id"]) break;
            }
            if ($share != null && $share->getIdUserDestination() == $_SESSION["user_id"]) {
                $role = $share->getRole();
            }
            else {
                $folderAux2 = $folder;
                if ($folderAux2->getIdParent() != null) {
                    $folderAux2 = ($container->get('get_folder_by_id_use_case'))($folderAux2->getIdParent());
                    while ($folderAux2->getIdUser() != $_SESSION["user_id"]) {
                        if ($folderAux2->getIdParent() == null) break;
                        $folderAux2 = ($container->get('get_folder_by_id_use_case'))($folderAux2->getIdParent());
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