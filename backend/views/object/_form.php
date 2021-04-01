<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use common\models\Task;

/* @var $this yii\web\View */
/* @var $model common\models\Objects */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="objects-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'object_id')->widget(Select2::class, [
                'data' => $model->listOtherObjects,
                'options' => ['placeholder' => 'Select parent object'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'task_ids')->dropDownList(Task::getDropdownListAll(), ['multiple' => true]) ?>
        </div>
        <div class="col-md-12">
            <?= $model->img? Html::img($model->img,['style'=>'max-width:500px']) : 'No picture' ?>
            <?= $form->field($model, '_imageFile')->fileInput() ?>
        </div>

    </div>








    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
