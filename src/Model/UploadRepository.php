<?php

namespace Pwbox\Model;

interface UploadRepository
{
    public function save(Upload $upload);
    public function get($id);
    public function getByUuid($uuid);
    public function getAll($id, $folderId);
}