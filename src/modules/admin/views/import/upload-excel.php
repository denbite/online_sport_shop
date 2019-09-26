<?php

/** @var \app\models\UploadForm $modelUpload */

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$this->title = 'Загрузка Excel таблицы';
$this->params['breadcrumbs'][] = [ 'label' => 'Импорт', 'url' => [ '/admin/import/index' ] ];
$this->params['breadcrumbs'][] = $this->title;

?>
<?php $form = ActiveForm::begin([
                                    'id' => 'import-upload_excel',
                                ]) ?>

<div class="box box-shadowed box-outline-success <?= !empty(Yii::$app->params['background']) ? Yii::$app->params['background'] : '' ?>">
    <div class="box-header with-border">
        <div class="right-float">
            <?= Html::submitButton(Html::tag('i', '&nbsp;', [ 'class' => 'fas fa-save' ]) . ' Сохранить',
                                   [ 'class' => 'btn btn-sm btn-success' ]) ?>
        </div>
    </div>
    <!-- /.box-header -->
    <div class="box-body form-element">
        
        <?= $form->field($modelUpload, 'excel')
                 ->widget(\kartik\file\FileInput::className(), [
                     'options' => [
                         'accept' => 'application/excel',
                     ],
                     'pluginOptions' => [
                         //                 'deleteUrl' => Url::toRoute([ '/admin/image/delete-image' ]),
                         'showUpload' => false,
                         'maxCount' => 1,
                         'minCount' => 0,
                         'overwriteInitial' => false,
                         //                 'initialPreview' => Image::getUrlsBySubject(Image::TYPE_ITEM,
                         //                                                             $modelColor->id),
                         'initialPreviewAsData' => true,
                         //                 'initialPreviewConfig' => Image::getInitialPreviewConfigBySubject(Image::TYPE_ITEM,
                         //                                                                                   $modelColor->id),
                     ],
                     //             'pluginEvents' => [
                     //                 'filesorted' => new \yii\web\JsExpression('function(event, params){
                     //                    $.post("' . Url::toRoute([ "/admin/image/sort-image", "id" => $modelColor->id ]) . '", {sort: params});
                     //                    }'),
                     //             ],
        
        
                 ])
        ?>

    </div>
</div>
<?php ActiveForm::end() ?>
