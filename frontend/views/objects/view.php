<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Objects */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Objects', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="row">
    <div class="col-md-12">
        <h1><?= Html::encode($this->title) ?></h1>
        <small><?= $model->getAttributeLabel('parentObjectTitle') ?>: <?= $model->parentObjectTitle ?></small>
    </div>
    <div class="col-md-6">
        <?= $model->img? Html::img($model->img,['style'=>'max-width:500px']) : 'No picture' ?>
    </div>
    <div class="col-md-6">
        Task list:
        <?php if($model->tasks): ?>
            <ul>
                <?php foreach ($model->tasks as $task): ?>
                    <li><?=  $task->title ?></li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            No tasks
        <?php endif; ?>
    </div>









</div>
