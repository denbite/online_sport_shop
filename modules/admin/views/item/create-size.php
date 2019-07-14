<?php

use app\components\models\Status;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Item */
/* @var $model_color app\models\ItemColor */
/* @var $model_size app\models\ItemColorSize */

$this->title = 'Создание размера';
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

    <div class="box box-shadowed box-outline-success <?= !empty(Yii::$app->params['background']) ? Yii::$app->params['background'] : '' ?>">
        <div class="box-header with-border">
            <div class="pull-right">
                <?= Html::submitButton(Html::tag('i', '&nbsp;', [ 'class' => 'fa fa-save' ]) . ' Сохранить',
                                       [ 'class' => 'btn btn-sm btn-success' ]) ?>
            </div>
        </div>
        <div class="box-body form-element">
            <?php $form = ActiveForm::begin([
                                                'id' => 'item_size-create',
                                            ]) ?>
            <div class="row">
                <div class="col-3">
                    <label class="control-label">Товар</label>
                    <?= \kartik\select2\Select2::widget([
                                                            'id' => 'item',
                                                            'name' => 'item',
                                                            'value' => $item,
                                                            'data' => $items,
                                                            'options' => [
                                                                'placeholder' => 'Выберите модель ...',
                                                            ],
                                                            'pluginOptions' => [
                                                                'allowClear' => true,
                                                            ],
                                                        ]) ?>
                </div>
                <div class="col-3">
                    <?= $form->field($model, 'color_id')
                             ->widget(\kartik\select2\Select2::className(), [
                                 'data' => !empty($colors) ? $colors : [],
                                 'options' => [ 'placeholder' => 'Выберите цвет товара ...', 'id' => 'item-color', 'disabled' => empty($colors) ],
                                 'pluginOptions' => [ 'allowClear' => true, ], ]) ?>
                </div>
                <div class="col">
                    <?= $form->field($model, 'size') ?>
                </div>
                <div class="col">
                    <?= $form->field($model, 'quantity') ?>
                </div>
                <div class="col">
                    <?= $form->field($model, 'price') ?>
                </div>
                <div class="col">
                    <?= $form->field($model, 'status')
                             ->widget(\kartik\switchinput\SwitchInput::className(), [ 'type' => \kartik\switchinput\SwitchInput::CHECKBOX,
                                 'pluginOptions' => [ 'onColor' => 'primary',
                                     'onText' => Status::getStatusesArray()[Status::STATUS_ACTIVE],
                                     'offText' => Status::getStatusesArray()[Status::STATUS_DISABLE], ], ]) ?>
                </div>
            </div>
            <?php ActiveForm::end() ?>
        </div>
    </div>



