<?php

namespace Pwbox\Model;

interface NotificationRepository
{
    public function save(Notification $notification);
}