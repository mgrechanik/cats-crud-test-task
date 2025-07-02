<?php

declare(strict_types=1);

namespace App\repositories;

use App\models\Cat;

/**
 * Репозиторий для всех выборок по котам, для sql
 */
class CatsReadRepository extends BaseRepository
{
    /**
     * Получаем всех котов/кошек, с фильтрацией
     * @param array $filters
     * @return Cats[]
     */
    public function getAllCatsObjects(array $filters): array
    {
        $res = [];
        $query = 'SELECT * FROM `cats`';
        $args = [];
        if ($filters) {
            $query .= ' WHERE ';
            foreach ($filters as $k => $v) {
                if ($args) {
                    $query .= ' AND ';
                }
                // тут безопасно, т.к. из кода задаю
                $query .= '`' . $k . '` = ? ';
                $args[] = $v;
            }
        }
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($args);
        while ($row = $stmt->fetch()) {
            $cat = new Cat();
            $cat->loadFromArray($row);
            $res[] = $cat;
        }
        return $res;
    }

    /**
     * Получаю  котов по полу и с исключением по id
     *
     * Исключение по id надо, чтобы в форме редактирования не задать котенку предком его же
     *
     * @param int $gender
     * @param int|null $excludeId
     * @return array
     */
    public function getCatsByGender(int $gender, int $excludeId = null): array
    {
        $res = [];
        $query = 'SELECT * FROM `cats` WHERE `gender` = ' . $gender;
        if ($excludeId) {
            $query .= ' AND `id` != ' . intval($excludeId);
        }
        $stmt = $this->pdo->query($query);
        while ($row = $stmt->fetch()) {
            $res[$row['id']] = $row;
        }
        return $res;
    }
}
