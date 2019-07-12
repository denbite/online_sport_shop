<?php

use kartik\switchinput\SwitchInput;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\AuthItem */
/* @var $permissions array */

$this->title = 'Создание роли';
$this->params['breadcrumbs'][] = [ 'label' => 'Товары', 'url' => [ 'index' ] ];
$this->params['breadcrumbs'][] = $this->title;
?>

<?php if (Yii::$app->session->hasFlash('success')): ?>
    <div class="alert alert-success alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                    aria-hidden="true">&times;</span></button>
        <?php echo Yii::$app->session->getFlash('success'); ?>
    </div>
<?php endif; ?>

<?php if (Yii::$app->session->hasFlash('error')): ?>
    <div class="alert alert-error alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                    aria-hidden="true">&times;</span></button>
        <?php echo Yii::$app->session->getFlash('error'); ?>
    </div>
<?php endif; ?>

<div class="box box-shadowed box-outline-success <?= !empty(Yii::$app->params['background']) ? Yii::$app->params['background'] : '' ?>">
    <div class="box-body form-element">
        <?php $form = ActiveForm::begin([
            'id' => 'item-create',
        ]) ?>
        <div class="row">
            <div class="col-lg-2 col-6">
                <?= $form->field($model, 'name') ?>
            </div>
            <div class="col-lg-2 col-6">
                <?= $form->field($model, 'description') ?>
            </div>
        </div>
        <?php foreach ($permissions as $group => $permission): ?>
            <h1><?= $group ?></h1>
            <div class="row">
                <?php foreach ($permission as $rule): ?>
                    <div class="col-lg-2 col-4">
                        <label class="control-label"><?= $rule ?></label>
                        <?= SwitchInput::widget([ 'name' => 'AuthItemChild[' . $rule . ']', 'value' => false ]) ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
        <div class="row">
            <div class="col">
                <?= Html::submitButton('Создать', [ 'class' => 'btn btn-success pull-right' ]) ?>
            </div>
        </div>
        <?php ActiveForm::end() ?>
    </div>
</div>



