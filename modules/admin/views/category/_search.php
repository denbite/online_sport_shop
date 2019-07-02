<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\CategorySearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="category-search">
    
    <?php $form = ActiveForm::begin([
                                        'action' => [ 'index' ],
                                        'method' => 'get',
                                    ]); ?>
    
    <?= $form->field($model, 'id') ?>
    
    <?= $form->field($model, 'lft') ?>
    
    <?= $form->field($model, 'rgt') ?>
    
    <?= $form->field($model, 'depth') ?>
    
    <?= $form->field($model, 'name') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', [ 'class' => 'btn btn-primary' ]) ?>
        <?= Html::resetButton('Reset', [ 'class' => 'btn btn-outline-secondary' ]) ?>
    </div>
    
    <?php ActiveForm::end(); ?>

</div>
