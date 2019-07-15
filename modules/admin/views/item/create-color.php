<?php

use app\components\models\Status;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Item */
/* @var $model_color app\models\ItemColor */
/* @var $model_size app\models\ItemColorSize */

$this->title = 'Создание цвета';
$this->params['breadcrumbs'][] = [ 'label' => 'Товары', 'url' => [ '/admin/item/index' ] ];
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
<?php $form = ActiveForm::begin([
    'id' => 'item_color-create',
]) ?>
    <div class="box box-shadowed box-outline-success <?= !empty(Yii::$app->params['background']) ? Yii::$app->params['background'] : '' ?>">
        <div class="box-header with-border">
            <div class="pull-right">
                <?= Html::submitButton(Html::tag('i', '&nbsp;', [ 'class' => 'fa fa-save' ]) . ' Сохранить',
                                       [ 'class' => 'btn btn-sm btn-success' ]) ?>
            </div>
        </div>
        <div class="box-body form-element">

            <div class="row">
                <div class="col">
                    <?= $form->field($model, 'item_id')->widget(\kartik\select2\Select2::className(), [
                        'data' => $items,
                        'options' => [
                            'placeholder' => 'Выберите модель ...',
                        ],
                        'pluginOptions' => [
                            'allowClear' => true,
                        ],
                    ]) ?>
                </div>
                <div class="col">
                    <?= $form->field($model, 'code') ?>
                </div>
                <div class="col">
                    <?= $form->field($model, 'color') ?>
                </div>
                <div class="col">
                    <?= $form->field($model, 'html')
                             ->widget(\kartik\color\ColorInput::className(), [
                                 'options' => [ 'readonly' => true, ],
                             ]) ?>
                </div>
                <div class="col">
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
        </div>
    </div>
<?php ActiveForm::end() ?>


