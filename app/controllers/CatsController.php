<?php

declare(strict_types=1);

namespace App\controllers;

use App\repositories\CatsReadRepository;
use App\repositories\CatsRepository;
use App\models\Cat;

class CatsController
{
    /**
     * Главная страница
     * @return array
     * @throws \Exception
     */
    public function main()
    {
        return [
            'title' => 'Главная',
            'content' => view('main.php'),
        ];
    }

    /**
     * Список страниц с фильтрацией
     * @return array
     * @throws \Exception
     */
    public function list()
    {
        $catsRep = new CatsReadRepository();
        $filters = [];
        if (!empty($_GET['gender'])) {
            $filters['gender'] = $_GET['gender'];
        }
        if (!empty($_GET['age'])) {
            $filters['age'] = $_GET['age'];
        }
        return [
            'title' => 'Список всех кошек',
            'content' => view('list.php', ['cats' => $catsRep->getAllCatsObjects($filters)]),
        ];
    }

    /**
     * Добавление кошки
     * @return array
     * @throws \Throwable
     */
    public function create()
    {
        $catsRep = new CatsReadRepository();
        $manageRep = new CatsRepository();
        $errors = [];
        $cat = new Cat();
        if (isset($_POST['create_cat'])) {
            if (empty($_POST['mother_id'])) {
                $_POST['mother_id'] = null;
            }
            try {
                $cat->loadFromArray($_POST);
                if (!($errors = $cat->validate())) {
                    $manageRep->create($cat);
                    // тут флеш сообщение надо бы
                    redirect('/?r=list');
                }
            } catch (\Throwable $e) {
                throw $e;
            }
        }
        $females = $catsRep->getCatsByGender(Cat::GENDER_FEMALE);
        $males = $catsRep->getCatsByGender(Cat::GENDER_MALE);
        return [
            'title' => 'Добавление кошки',
            'content' => view('create.php', ['cat' => $cat, 'females' => $females, 'males' => $males, 'errors' => $errors]),
        ];
    }

    /**
     * Редактирование кошки
     * @return array
     * @throws \Throwable
     */
    public function edit()
    {
        $catsRep = new CatsReadRepository();
        $manageRep = new CatsRepository();
        $id = $_GET['id'] ?? null;
        if (empty($id) || ! ($cat = $manageRep->find(intval($id)))) {
            throw new \Exception('Страница не найдена');
        }
        $errors = [];
        if (isset($_POST['edit_cat'])) {
            unset($_POST['id']);
            if (empty($_POST['mother_id'])) {
                $_POST['mother_id'] = null;
            }
            try {
                $cat->loadFromArray($_POST);
                if (!($errors = $cat->validate())) {
                    $manageRep->update($cat);
                    // тут флеш сообщение тоже  надо
                    redirect('/?r=list');
                }
            } catch (\Throwable $e) {
                throw $e;
            }
        }
        $females = $catsRep->getCatsByGender(Cat::GENDER_FEMALE, $cat->id);
        $males = $catsRep->getCatsByGender(Cat::GENDER_MALE, $cat->id);
        return [
            'title' => 'Редактирование кошки',
            'content' => view('edit.php', ['cat' => $cat, 'females' => $females, 'males' => $males, 'errors' => $errors]),
        ];
    }

    /**
     * Удаление кошки
     * @return array
     * @throws \Throwable
     */
    public function delete()
    {
        $manageRep = new CatsRepository();
        $id = $_GET['id'] ?? null;
        if (empty($id) || ! ($cat = $manageRep->find(intval($id)))) {
            throw new \Exception('Страница не найдена');
        }
        if (isset($_POST['delete_cat'])) {
            try {
                $manageRep->deleteById(intval($id));
                // и тут флеш сообщение
                redirect('/?r=list');
            } catch (\Throwable $e) {
                throw $e;
            }
        }
        return [
            'title' => 'Удаление кошки',
            'content' => view('delete.php', ['cat' => $cat]),
        ];
    }
}
