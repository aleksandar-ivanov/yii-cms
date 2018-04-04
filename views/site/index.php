<?php

/* @var $this yii\web\View */

$this->title = 'My Yii Application';
?>
<div class="container">
    <h1>Countries</h1>
    <ul>
      <?php foreach ($countries as $country): ?>
        <li>
            <?= \yii\helpers\Html::encode("{$country->name} ({$country->code})") ?>:
            <?= $country->population ?>
        </li>
      <?php endforeach; ?>
    </ul>

    <?= \yii\widgets\LinkPager::widget(['pagination' => $pagination]) ?>

    <label>
        <div class="icheckbox disabled">
            <input type="checkbox" name="quux[1]" disabled>
        </div>
        Foo
    </label>
</div>