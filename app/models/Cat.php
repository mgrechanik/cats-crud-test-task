<?php

namespace App\models;

use App\repositories\CatsReadRepository;

/**
 * Модель кота
 */
class Cat
{
    public const GENDER_MALE = 1;
    public const GENDER_FEMALE = 2;

    public const GENDER_MALE_NAME = 'Кот';
    public const GENDER_FEMALE_NAME = 'Кошка';

    public ?int $id = null;
    public string $name = '';
    public int $gender = self::GENDER_FEMALE;
    public int $age = 1;

    public ?int $mother_id = null;

    public array $father_ids = [];

    /**
     * Загрузка из массива, из БД или реквеста
     * @param array $array
     * @return void
     */
    public function loadFromArray(array $array): void
    {
        if (isset($array['id'])) {
            $this->id = $array['id'];
        }
        $this->name = $array['name'] ?? '';
        $this->gender = $array['gender'] ?? '';
        $this->age = $array['age'] ?? 0;
        $this->mother_id = $array['mother_id'] ?? null;
        $this->father_ids = $array['father_ids'] ?? [];
    }

    /**
     * Валидация
     * @return array Массив ошибок
     */
    public function validate(): array
    {
        $catRep = new CatsReadRepository();
        $errors = [];
        if (empty($this->name)) {
            $errors[] = 'Не заполнено поле Имя';
        }
        if (empty($this->gender)) {
            $errors[] = 'Не заполнено поле Пол';
        }
        $this->age = (int) $this->age;
        if (empty($this->age) || $this->age < 0) {
            $errors[] = 'Не заполнено поле или неверен Возраст';
        }
        if ($this->mother_id) {
            $allowedIds = array_keys($catRep->getCatsByGender(self::GENDER_FEMALE, $this->id));
            if (!in_array($this->mother_id, $allowedIds)) {
                $errors[] = 'Не допустимое значение поле Мама';
            }
        }
        if ($this->father_ids) {
            $allowedIds = array_keys($catRep->getCatsByGender(self::GENDER_MALE, $this->id));
            foreach ($this->father_ids as $father_id) {
                if (!in_array($father_id, $allowedIds)) {
                    $errors[] = 'Не допустимое значение поле Отцы';
                    break;
                }
            }
        }
        // Можно и другие правила валидации придумать, для котов и кошек

        return $errors;

    }

    /**
     * Текстовое представление пола
     * @return string
     */
    public function getGenderName(): string
    {
        return $this->gender == self::GENDER_FEMALE ? self::GENDER_FEMALE_NAME : self::GENDER_MALE_NAME;
    }

    /**
     * Список значений пола, для форм
     * @return string[]
     */
    public static function getGendersList(): array
    {
        return [
            self::GENDER_FEMALE => self::GENDER_FEMALE_NAME,
            self::GENDER_MALE => self::GENDER_MALE_NAME,
        ];
    }

}
