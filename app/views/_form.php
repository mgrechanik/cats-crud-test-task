<?php
$genders = \App\models\Cat::getGendersList();
?>
<?php if ($errors) { ?>
    <h3>Ошибки</h3>
    <li class="errors">
        <?php
            foreach ($errors as $error) {
                print '<li>' . html($error) . '</li>';
            }
    ?>
    </li>
<?php } ?>
<form method="post" action="<?= $action ?>">
    <label for="name">Имя котенка:</label><br>
    <input type="text" id="name" name="name" value="<?= html($cat->name) ?>" required><br><br>

    <label for="gender">Пол:</label><br>
    <select id="gender" name="gender" required>
        <?php
        foreach ($genders as $k => $v) {
            $selected = $k == $cat->gender ? ' selected' : '';
            print '<option value="' . $k . '" ' . $selected . '>' . html($v) . '</option>';
        }
?>

    </select><br><br>

    <label for="age">Возраст (в годах):</label><br>
    <input type="number" id="age" name="age"  value="<?= html($cat->age) ?>" min="0" required><br><br>

    <label for="mother_id">Мама:</label><br>
    <select id="mother_id" name="mother_id">
        <option value="">-- выбрать маму --</option>
        <?php foreach ($females as $female):
            $selected = $female['id'] == $cat->mother_id ? ' selected' : '';
            ?>
            <option value="<?= $female['id'] ?>"  <?= $selected ?>>
                <?= html($female['name']) ?>
            </option>
        <?php endforeach; ?>
    </select><br><br>


    <label for="father_ids">Папы (можно несколько):</label><br>
    <select id="father_ids" name="father_ids[]" multiple size="4">
        <?php foreach ($males as $male):
            $selected = in_array($male['id'], $cat->father_ids) ? ' selected' : '';
            ?>
            <option value="<?= $male['id'] ?>" <?= $selected ?>>
                <?= html($male['name']) ?>
            </option>
        <?php endforeach; ?>
    </select><br><br>
    <button name="<?= $buttonName ?>" type="submit"><?= $buttonTitle?></button>
</form>