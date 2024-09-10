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
            'action' => ['apple/eat', 'id' => $model->id],
            'method' => 'post',
        ]); ?>

        <?= $form->field($model, 'eaten_percent')->textInput(['type' => 'number', 'min' => 0, 'max' => 100]) ?>

        <div class="form-group">
            <?= Html::submitButton('Eat', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>
