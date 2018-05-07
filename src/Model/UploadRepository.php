<?php

namespace Pwbox\Model;

interface UploadRepository
{
    public function save(Upload $upload);
}