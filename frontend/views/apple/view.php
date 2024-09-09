<?php
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Apple */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Apples', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="apple-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
        <?= Html::a('Fall', ['fall', 'id' => $model->id], ['class' => 'btn btn-warning']) ?>
        <?= Html::a('Eat', ['eat', 'id' => $model->id], ['class' => 'btn btn-info']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'color',
            'created_at:datetime',
            'fallen_at:datetime',
            'status',
            'size',
        ],
    ]) ?>

    <?php if ($model->status === \app\models\Apple::STATUS_FALLEN): ?>
        <div class="form-group">
            <?= Html::beginForm(['eat', 'id' => $model->id], 'post') ?>
            <?= Html::label('Eat Percentage') ?>
            <?= Html::input('number', 'percent', 0, ['min' => 0, 'max' => 100, 'step' => 0.1]) ?>
            <?= Html::submitButton('Eat', ['class' => 'btn btn-info']) ?>
            <?= Html::endForm() ?>
        </div>
    <?php endif; ?>

</div>
