<?php
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $model app\models\Apple */

$this->title = 'Make the Apple Fall';
$this->params['breadcrumbs'][] = ['label' => 'Apples', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="apple-fall">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Are you sure you want to make this apple fall to the ground?</p>

    <?= Html::beginForm(Url::to(['apple/fall', 'id' => $model->id]), 'post') ?>
        <?= Html::submitButton('Make Fall', ['class' => 'btn btn-danger']) ?>
    <?= Html::endForm() ?>
</div>
