<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Apple */
/* @var $form yii\widgets\ActiveForm */

$this->title = 'Update Apple: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Apples', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="apple-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="apple-form">

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'color')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'size')->textInput() ?>

        <?= $form->field($model, 'created_at')->hiddenInput(['value' => $model->created_at])->label(false) ?>

        <?= $form->field($model, 'status')->textInput(['value' => $model->status]) ?>

        <div class="form-group">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

</div>
