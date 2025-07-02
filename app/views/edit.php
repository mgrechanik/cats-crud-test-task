<h2>Изменение Кошки/Кота</h2>

<a href="/?r=list">Список всех кошек</a>

<?php
$buttonName = 'edit_cat';
$buttonTitle = 'Отредактировать котенка';
$action = '/?r=edit&amp;id=' . $cat->id;
include __DIR__ . '/_form.php';
