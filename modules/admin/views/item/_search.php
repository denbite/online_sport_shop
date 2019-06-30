<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\ItemSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="item-search">
    
    <?php $form = ActiveForm::begin([
                                        'action' => [ 'index' ],
                                        'method' => 'get',
                                    ]); ?>
    
    <?= $form->field($model, 'id') ?>
    
    <?= $form->field($model, 'category_id') ?>
    
    <?= $form->field($model, 'firm') ?>
    
    <?= $form->field($model, 'model') ?>
    
    <?= $form->field($model, 'collection') ?>
    
    <?php // echo $form->field($model, 'code') ?>
    
    <?php // echo $form->field($model, 'sale') ?>
    
    <?php // echo $form->field($model, 'status') ?>
    
    <?php // echo $form->field($model, 'created_at') ?>
    
    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', [ 'class' => 'btn btn-primary' ]) ?>
        <?= Html::resetButton('Reset', [ 'class' => 'btn btn-outline-secondary' ]) ?>
    </div>
    
    <?php ActiveForm::end(); ?>

</div>
