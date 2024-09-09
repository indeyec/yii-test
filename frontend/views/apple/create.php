<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Apple */
/* @var $form yii\widgets\ActiveForm */

$this->title = 'Create Apple';
$this->params['breadcrumbs'][] = ['label' => 'Apples', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="apple-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="apple-form">

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'color')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'size')->textInput() ?>

        <?= $form->field($model, 'created_at')->hiddenInput(['value' => time()])->label(false) ?>

        <?= $form->field($model, 'status')->hiddenInput(['value' => \app\models\Apple::STATUS_PENDING])->label(false) ?>

        <div class="form-group">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

</div>
