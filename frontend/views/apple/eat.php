<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $model app\models\Apple */

$this->title = 'Eat Apple';
$this->params['breadcrumbs'][] = ['label' => 'Apples', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="apple-eat">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Current status: <?= Html::encode($model->status) ?></p>
    <p>Size: <?= Html::encode($model->size * 100) ?>%</p>

    <div class="apple-form">
        <?php $form = ActiveForm::begin([
            'action' => ['apple/eat', 'id' => $model->id], // Action направлен на метод eat контроллера
            'method' => 'post',
        ]); ?>

        <!-- Поле для ввода процента, сколько съесть -->
        <div class="form-group">
            <?= Html::label('Percent to eat', 'percent') ?>
            <?= Html::input('number', 'percent', null, [
                'class' => 'form-control',
                'min' => 0,
                'max' => 100,
                'step' => 0.1, // Позволяет вводить дробные значения
                'required' => true, // Поле обязательно для заполнения
            ]) ?>
        </div>

        <div class="form-group">
            <?= Html::submitButton('Eat', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>
