# Функционал CRUD-а для котов и кошек на чистом php, без использования фреймворков 

## Пояснение к сделанному

- Собрал минималистичный движок, без использования посторонних библиотек
- Сделал CRUD для котенка, согласно ТЗ
- Не успел сделать (не указано в ТЗ), но на будущее - пагинацию на странице списка, CSRF защиту форм, флеш сообщения
- Проверил работоспособность, все работает как и задумано

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