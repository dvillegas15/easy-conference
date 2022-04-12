<?php

namespace App\Interfaces\Core;

use App\Models\Core\Person;
use App\Models\Core\User;
use App\Interfaces\RepositoryInterface;

interface UserRepositoryInterface extends RepositoryInterface
{
    public function isUnique($document, $email): bool;
    public function getByDocument($document): ?User;
}
