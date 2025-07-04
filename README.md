# Функционал CRUD-а для котов и кошек на чистом php, без использования фреймворков 

## Текст тестового задания

>Тестовая задача:
> Организовать учёт кошек. Про каждую кошку мы знаем кличку, пол и возраст(в годах). Нужно что бы была возможность добавлять, редактировать и удалять кошек из БД. Нужна возможность фильтровать кошек по возрасту и полу. У кошек периодически появляется потомство. Необходимо иметь возможность указывать мать нового котенка и, в связи с особенностями поведения кошачьих, множество возможных отцов.     
> Требуется создать приложение без использования фреймворков.

## Пояснение к сделанному

- Собрал минималистичный движок, без использования посторонних библиотек
- Сделал CRUD для кота/кошки, согласно ТЗ
- Не успел сделать (не указано в ТЗ), но на будущее - пагинацию на странице списка, CSRF защиту форм, флеш сообщения
- Не делал DI и прочие ООП полезности, которые в реальном проекте, конечно же, нужны
- Проверил работоспособность, все работает как и задумано
- ```/web/index.php``` - точка входа в приложение, ```web/``` - каталог, доступный извне

## Структура БД следующая

```sql
CREATE TABLE cats (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    gender INT NOT NULL,
    age INT NOT NULL,
    mother_id INT NULL,
    INDEX idx_gender (gender),
    INDEX idx_age (age),
    CONSTRAINT fk_mother
        FOREIGN KEY (mother_id)
        REFERENCES cats(id)
        ON DELETE SET NULL
        ON UPDATE CASCADE
);

CREATE TABLE cat_fathers (
    cat_id INT NOT NULL,
    father_id INT NOT NULL,
    PRIMARY KEY (cat_id, father_id),
    CONSTRAINT fk_cat
        FOREIGN KEY (cat_id)
        REFERENCES cats(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    CONSTRAINT fk_father
        FOREIGN KEY (father_id)
        REFERENCES cats(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);
```

## Как это выглядит

![demo of the cats crud functionality ](https://raw.githubusercontent.com/mgrechanik/cats-crud-test-task/refs/heads/main/cats_test_task.png "CRUD котов")