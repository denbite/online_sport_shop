<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Promotion */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="promotion-form">
    
    <?php $form = ActiveForm::begin(); ?>
    
    <?= $form->field($model, 'type')->textInput() ?>
    
    <?= $form->field($model, 'sale')->textInput() ?>
    
    <?= $form->field($model, 'status')->textInput() ?>
    
    <?= $form->field($model, 'publish_from')->textInput() ?>
    
    <?= $form->field($model, 'publish_to')->textInput() ?>
    
    <?= $form->field($model, 'updated_at')->textInput() ?>
    
    <?= $form->field($model, 'created_at')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', [ 'class' => 'btn btn-success' ]) ?>
    </div>
    
    <?php ActiveForm::end(); ?>

</div>
