<?php

declare(strict_types=1);

namespace App\repositories;

use App\models\Cat;

/**
 * Репозиторий сохранения в БД котов
 */
class CatsRepository extends BaseRepository
{
    /**
     * Создаем нового
     * @param Cat $cat
     * @return void
     * @throws \Exception
     */
    public function create(Cat $cat)
    {
        $this->pdo->beginTransaction();
        try {
            $sql = 'INSERT INTO `cats` (`name`, `gender`, `age`, `mother_id`) VALUES (?, ?, ?, ?)';
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$cat->name, $cat->gender, $cat->age, $cat->mother_id ? $cat->mother_id : null]);
            $lastId = $this->pdo->lastInsertId();
            foreach ($cat->father_ids as $father_id) {
                $sql = 'INSERT INTO `cat_fathers` (`cat_id`, `father_id`) VALUES (?, ?)';
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute([$lastId, $father_id]);
            }
            $this->pdo->commit();
        } catch (\Throwable $e) {
            $this->pdo->rollBack();
            throw new \Exception('Не удалось создать котенка');
        }

    }

    /**
     * Редактирую существующего
     *
     * @param Cat $cat
     * @return void
     * @throws \Exception
     */
    public function update(Cat $cat)
    {
        $this->pdo->beginTransaction();
        try {
            $sql = 'UPDATE `cats` SET `name` = ?, `gender` = ?, `age` = ?, `mother_id` = ? WHERE id=' . intval($cat->id);
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$cat->name, $cat->gender, $cat->age, $cat->mother_id ? $cat->mother_id : null]);

            $sql = 'DELETE FROM `cat_fathers`  WHERE `cat_id`= ?';
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$cat->id]);

            foreach ($cat->father_ids as $father_id) {
                $sql = 'INSERT INTO `cat_fathers` (`cat_id`, `father_id`) VALUES (?, ?)';
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute([$cat->id, $father_id]);
            }
            $this->pdo->commit();
        } catch (\Throwable $e) {
            $this->pdo->rollBack();
            throw new \Exception('Не удалось отредактировать котенка');
        }

    }

    /**
     * Удаляю
     * @param int $id
     * @return void
     */
    public function deleteById(int $id)
    {
        $sql = 'DELETE FROM `cats`  WHERE `id`= ?';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
    }

    /**
     * Найти кота по id
     *
     * @param int $id
     * @return Cat|null
     */
    public function find(int $id): ?Cat
    {
        $stmt = $this->pdo->query('SELECT * FROM `cats` WHERE `id` =' . intval($id));
        if ($row = $stmt->fetch()) {
            $row['father_ids'] = [];
            $stmt2 = $this->pdo->query('SELECT * FROM `cat_fathers` WHERE `cat_id` =' . intval($id));
            while ($row2 = $stmt2->fetch()) {
                $row['father_ids'][] = $row2['father_id'];
            }
            $cat = new Cat();
            $cat->loadFromArray($row);
            return $cat;
        }
        return null;
    }
}
