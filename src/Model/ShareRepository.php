<?php

namespace Pwbox\Model;

interface ShareRepository
{
    public function save(Share $share);
    public function getAll($userId);
}