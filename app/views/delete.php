<?php

?>
<h2>Удаление Кошки/Кота - <?=  html($cat->name)?></h2>

<a href="/?r=list">Список всех кошек</a>


<form method="post" action="/?r=delete&amp;id=<?= $cat->id ?>">
    Вы уверены что хотите удалить этого кота/кошку?
    <button name="delete_cat" type="submit">Удалить</button>
</form>



