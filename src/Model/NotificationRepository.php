<?php

namespace Pwbox\Model;

interface NotificationRepository
{
    public function save(Notification $notification);
    public function getAll($userId);
    public function getLastFive($userId);
}