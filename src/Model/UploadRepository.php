<?php

namespace Pwbox\Model;

interface UploadRepository
{
    public function save(Upload $upload);
    public function getById($id);
    public function getByUuid($uuid);
    public function getAll($id, $folderId);
    public function getFolderSizeInBytes($folderId);
    public function getRootFolderSizeInBytes($userId);
    public function rename(Upload $upload);
    public function getChildFiles($id);
    public function delete($id);
}