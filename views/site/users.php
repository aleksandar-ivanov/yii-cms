<?php

/* @var $this yii\web\View */

$this->title = 'User management';
?>

<?php foreach ($users as $user): ?>
    <li>
        <?= $user ?>:
    </li>
<?php endforeach; ?>