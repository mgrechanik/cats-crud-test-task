<?php

declare(strict_types=1);

namespace App\repositories;

use App\services\Db;

abstract class BaseRepository
{
    protected \PDO $pdo;

    public function __construct()
    {
        $this->pdo = Db::getInstance()->getPdo();
    }
}
