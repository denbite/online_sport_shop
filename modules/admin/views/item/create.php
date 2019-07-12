<?php

use app\components\models\Status;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Item */
/* @var $modelDescription app\models\ItemDescription */
/* @var $categories array */

$this->title = 'Создание товара';
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
                    <?= $form->field($model, 'category_id')->widget(\kartik\select2\Select2::className(), [
                        'data' => $categories,
                        'options' => [
                            'placeholder' => 'Выберите категорию ...',
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ],
                    ]) ?>
                </div>
                <div class="col-lg-2 col-6">
                    <?= $form->field($model, 'firm') ?>
                </div>
                <div class="col-lg-2 col-6">
                    <?= $form->field($model, 'model') ?>
                </div>
                <div class="col-lg-1 col-6">
                    <?= $form->field($model, 'collection') ?>
                </div>
                <div class="col-lg-2 col-6">
                    <?= $form->field($model, 'code') ?>
                </div>
                <div class="col-lg-1 col-6">
                    <?= $form->field($model, 'rate') ?>
                </div>
                <div class="col-lg-2 col-6">
                    <?= $form->field($model, 'status')
                             ->widget(\kartik\switchinput\SwitchInput::className(), [
                                 'type' => \kartik\switchinput\SwitchInput::CHECKBOX,
                                 'pluginOptions' => [
                                     'onColor' => 'primary',
                                     'onText' => Status::getStatusesArray()[Status::STATUS_ACTIVE],
                                     'offText' => Status::getStatusesArray()[Status::STATUS_DISABLE],
                                 ],
                             ]) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-3 col-6">
                    <?= $form->field($modelDescription, 'small_text')->textarea() ?>
                </div>
                <div class="col-lg-3 col-6">
                    <?= $form->field($modelDescription, 'small_list')->textarea() ?>
                </div>
                <div class="col-lg-3 col-6">
                    <?= $form->field($modelDescription, 'text')->textarea() ?>
                </div>
                <div class="col-lg-3 col-6">
                    <?= $form->field($modelDescription, 'list')->textarea() ?>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <?= Html::submitButton('Создать', [ 'class' => 'btn btn-success pull-right' ]) ?>
                </div>
            </div>
            <?php ActiveForm::end() ?>
        </div>
    </div>



