<?php

use app\components\models\Status;
use app\models\Image;
use kartik\datetime\DateTimePicker;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Banner */
/* @var $modelUpload app\models\UploadForm */
/* @var $types array */
/* @var $items array */
/* @var $current array */

$this->title = $model->isNewRecord ? 'Создание баннера' : 'Редактирование баннера: ' . $model->title;
$this->params['breadcrumbs'][] = [ 'label' => 'Баннеры', 'url' => [ 'index' ] ];
$this->params['breadcrumbs'][] = $this->title;
?>
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css">
<script defer src="https://use.fontawesome.com/releases/v5.3.1/js/all.js" crossorigin="anonymous"></script>
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
    'id' => $model->isNewRecord ? 'promotion-create' : 'promotion-update',
]) ?>
<div class="box box-shadowed box-outline-success <?= !empty(Yii::$app->params['background']) ? Yii::$app->params['background'] : '' ?>">
    <div class="box-header with-border">
        <div class="pull-right">
            <?= Html::submitButton(Html::tag('i', '&nbsp;', [ 'class' => 'fa fa-save' ]) . ' Сохранить',
                [ 'class' => 'btn btn-sm btn-success' ]) ?>
        </div>
    </div>
    <div class="box-body form-element">
        <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#main" role="tab"><span
                            class="hidden-sm-up"><i class="ion-home"></i></span> <span
                            class="hidden-xs-down">Основные</span></a></li>
            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#photo" role="tab"><span
                            class="hidden-sm-up"><i class="ion-person"></i></span> <span
                            class="hidden-xs-down">Фото</span></a>
            </li>
        </ul>
        <!-- Tab panes -->
        <div class="tab-content tabcontent-border">
            <div class="tab-pane active" id="main" role="tabpanel">
                <div class="pad">
                    <div class="row">
                        <div class="col-lg-2 col-6">
                            <?= $form->field($model, 'title')->textInput() ?>
                        </div>
                        <div class="col-lg-2 col-6">
                            <?= $form->field($model, 'link')->textInput() ?>
                        </div>
                        <div class="col-lg-2 col-6">
                            <?= $form->field($model, 'link_title')->textInput() ?>
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
                        <div class="col-lg-3 col-6">
                            <?= $form->field($model, 'publish_from')
                                     ->widget(
                                         DateTimePicker::classname(), [
                                             'removeButton' => false,
                                             'readonly' => true,
                                             'options' => [
                                                 'value' => $model->publish_from ? date("d.m.Y H:i",
                                                     $model->publish_from) : date("d.m.Y H:i",
                                                     time()),
                                                 'placeholder' => 'Дата начала',
                                             ],
                                             'pluginOptions' => [
                                                 'autoclose' => true,
                                                 'todayBtn' => true,
                                                 'format' => 'dd.mm.yyyy HH:ii',
                                                 //                                                    'weekStart' => 1,
                                                 //'startDate' => $startDate,//дата начала
                                             ],
                                         ]
                                     ); ?>

                        </div>
                        <div class="col-lg-3 col-6">
                            <?= $form->field($model, 'publish_to')
                                     ->widget(
                                         DateTimePicker::classname(), [
                                             'removeButton' => false,
                                             'readonly' => true,
                                             'options' => [
                                                 'value' => $model->publish_to ? date("d.m.Y H:i",
                                                     $model->publish_to) : date("d.m.Y H:i",
                                                     time()),
                                                 'placeholder' => 'Дата окончания',
                                             ],
                                             'pluginOptions' => [
                                                 'autoclose' => true,
                                                 'todayBtn' => true,
                                                 'format' => 'dd.mm.yyyy HH:ii',
                                                 //                                                    'weekStart' => 1,
                                                 //'startDate' => $startDate,//дата начала
                                             ],
                                         ]
                                     ); ?>

                        </div>
                        <div class="col-lg-8 col-12">
                            <?= $form->field($model, 'description')->widget(\mihaildev\ckeditor\CKEditor::className(), [
                                'options' => [
                                    'preset' => 'basic',
                                ],
                            ]) ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane" id="photo" role="tabpanel">
                <div class="pad">
                    <div class="row">
                        <div class="col-12">
                            <?= $form->field($modelUpload, 'image')->widget(\kartik\file\FileInput::className(), [
                                'name' => 'UploadForm',
                                'attribute' => 'image',
                                'options' => [
                                    'accept' => 'image/*',
                                ],
                                'pluginOptions' => [
                                    'deleteUrl' => Url::toRoute([ '/admin/image/delete-image' ]),
                                    'showUpload' => false,
                                    'maxCount' => 1,
                                    'minCount' => 0,
                                    'overwriteInitial' => false,
                                    'initialPreview' => Image::getUrlsBySubject(Image::TYPE_BANNER,
                                        $model->id),
                                    'initialPreviewAsData' => true,
                                    'initialPreviewConfig' => Image::getInitialPreviewConfigBySubject(Image::TYPE_BANNER,
                                        $model->id),
                                ],
                            
                            
                            ])
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
<?php ActiveForm::end() ?>




