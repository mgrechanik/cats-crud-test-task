<?php
$genders = \App\models\Cat::getGendersList();
?>
<h2>Список всех кошек</h2>

<a href="/?r=main">Главная страница</a>
<a href="/?r=create">Добавить Кошку/Кота</a>
<form method="get" action="/?r=list">
    <label for="age">Возраст (в годах):</label><br>
    <input type="number" id="age" name="age"  value="<?= html($_GET['age'] ?? '') ?>"><br><br>

    <label for="gender">Пол:</label><br>
    <select id="gender" name="gender" >
        <option value=""></option>
        <?php
        foreach ($genders as $k => $v) {
            $selected = $k == $_GET['gender'] ? ' selected' : '';
            print '<option value="' . html($k) . '" ' . $selected . '>' . html($v) . '</option>';
        }
?>

    </select><br><br>
    <input type="hidden" name="r" value="list">
    <button name="filter" type="submit">Фильтровать</button>
</form>
<div>
    <table style="border:2px solid">
        <tr>
            <th>id</th>
            <th>Имя</th>
            <th>Пол</th>
            <th>Возраст</th>
            <th>Действие</th>
        </tr>
        <?php foreach ($cats as $cat) { ?>
            <tr>
                <td><?= $cat->id ?></td>
                <td><?= html($cat->name) ?></td>
                <td><?= $cat->getGenderName() ?></td>
                <td><?= html($cat->age) ?></td>
                <td><a href="/?r=edit&amp;id=<?= $cat->id ?>">Редактировать</a>
                    <a href="/?r=delete&amp;id=<?= $cat->id ?>">Удалить</a>
                </td>
            </tr>
        <?php } ?>
    </table>
</div>