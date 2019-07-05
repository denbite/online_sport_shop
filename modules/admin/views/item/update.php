<?php

use app\components\models\Status;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Item */
/* @var $modelColors app\models\ItemColor */
/* @var $modelColorsSizes app\models\ItemColorSize */
/* @var $modelUploads app\models\UploadForm */

$this->title = 'Редактирование: ' . $model->firm . ' ' . $model->model;
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
    'id' => 'user-update',
]) ?>
<div class="box box-shadowed box-outline-success <?= !empty(Yii::$app->params['background']) ? Yii::$app->params['background'] : '' ?>">
    <div class="box-header with-border">
        <div class="pull-right">
            <?= Html::submitButton(Html::tag('i', '&nbsp;', [ 'class' => 'fa fa-save' ]) . ' Сохранить',
                [ 'class' => 'btn btn-sm btn-success' ]) ?>
        </div>
    </div>
    <!-- /.box-header -->
    <div class="box-body form-element">
        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#main" role="tab"><span
                            class="hidden-sm-up"><i class="ion-home"></i></span> <span
                            class="hidden-xs-down">Основные</span></a></li>
            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#color" role="tab"><span
                            class="hidden-sm-up"><i class="ion-person"></i></span> <span
                            class="hidden-xs-down">Цвета</span></a>
            </li>
            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#size" role="tab"><span
                            class="hidden-sm-up"><i class="ion-email"></i></span> <span
                            class="hidden-xs-down">Размеры</span></a>
            </li>
            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#photo" role="tab"><span
                            class="hidden-sm-up"><i class="ion-email"></i></span> <span class="hidden-xs-down">Фотографии</span></a>
            </li>
            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#description" role="tab"><span
                            class="hidden-sm-up"><i class="ion-email"></i></span> <span
                            class="hidden-xs-down">Описание</span></a>
            </li>
        </ul>
        <!-- Tab panes -->
        <div class="tab-content tabcontent-border">
            <div class="tab-pane active" id="main" role="tabpanel">
                <div class="pad">
                    <div class="row">
                        <div class="col">
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
                        <div class="col">
                            <?= $form->field($model, 'firm') ?>
                        </div>
                        <div class="col">
                            <?= $form->field($model, 'model') ?>
                        </div>
                        <div class="col">
                            <?= $form->field($model, 'collection') ?>
                        </div>
                        <div class="col">
                            <?= $form->field($model, 'code') ?>
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
            <div class="tab-pane" id="color" role="tabpanel">
                <div class="pad">
                    <?php foreach ($modelColors as $index => $modelColor): ?>
                        <div class="row">
                            <div class="col">
                                <?= $form->field($modelColor, '[' . $index . ']code') ?>
                            </div>
                            <div class="col">
                                <?= $form->field($modelColor, '[' . $index . ']color') ?>
                            </div>
                            <div class="col">
                                <?= $form->field($modelColor, '[' . $index . ']status')
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
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="tab-pane" id="size" role="tabpanel">
                <div class="pad">
                    <?php foreach ($modelColorsSizes as $color => $modelColorSize): ?>
                        <h1><?= $color ?></h1>
                        <?php foreach ($modelColorSize as $index => $modelSize): ?>
                            <div class="row">
                                <div class="col">
                                    <?= $form->field($modelSize, '[' . $color . '][' . $index . ']size') ?>
                                </div>
                                <div class="col">
                                    <?= $form->field($modelSize, '[' . $color . '][' . $index . ']quantity') ?>
                                </div>
                                <div class="col">
                                    <?= $form->field($modelSize, '[' . $color . '][' . $index . ']price') ?>
                                </div>
                                <div class="col">
                                    <?= $form->field($modelSize, '[' . $color . '][' . $index . ']status')
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
                        <?php endforeach; ?>
                    
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="tab-pane" id="photo" role="tabpanel">
                <div class="pad">
                    <?php foreach ($modelUploads as $color => $modelUpload): ?>
                        <h1><?= $color ?></h1>
                        <?= $form->field($modelUpload, '[' . $color . ']images[]')
                                 ->widget(\kartik\file\FileInput::className(), [
                                     'options' => [
                                         'multiple' => true,
                                         'accept' => 'image/*',
                                     ],
                                 ])
                        ?>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="tab-pane" id="description" role="tabpanel">
                <div class="pad">
                    <h1>Coming Soon!</h1>
                </div>
            </div>
        </div>
    </div>
    <!-- /.box-body -->
</div>
<!-- /.box -->

<?php ActiveForm::end() ?>

