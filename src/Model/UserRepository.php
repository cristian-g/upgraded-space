<?php
/**
 * Created by PhpStorm.
 * User: marc
 * Date: 4/12/18
 * Time: 8:23 PM
 */

namespace Pwbox\Model;


interface UserRepository
{
    public function save(User $user);
}