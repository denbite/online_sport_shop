<?php

use app\components\models\Status;
use mihaildev\ckeditor\CKEditor;
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
<?php $form = ActiveForm::begin([
    'id' => 'item-create',
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
                <div class="col-lg-6 col-12">
                    <?= $form->field($modelDescription, 'small_text')->widget(CKEditor::className(), [
                        'editorOptions' => [
                            'preset' => 'basic',
                        ],
                    ]) ?>
                </div>
                <div class="col-lg-6 col-12">
                    <div id="dynamic_fields-small_list">
                        <?= $form->field($modelDescription, 'small_list_array[]')->textInput() ?>
                        <a id="add-field" class="btn btn-info"><i class="glyphicon glyphicon-plus"></i></a>
                        <a id="minus-field" class="btn btn-info" style="display: none;"><i
                                    class="glyphicon glyphicon-minus"></i></a>
                    </div>
                </div>
                <div class="col-lg-6 col-12">
                    <?= $form->field($modelDescription, 'text')->widget(CKEditor::className(), [
                        'editorOptions' => [
                            'preset' => 'full',
                        ],
                    ]) ?>
                </div>
                <div class="col-lg-6 col-12 py-5">
                    <div id="dynamic_fields-list">
                        <div class="form-group field-itemdescription-list_array-key" style="display: flex;">
                            <label class="control-label" for="itemdescription-list_array-key">Keys</label>
                            <input type="text" id="itemdescription-list_array-key" class="form-control"
                                   name="ItemDescription[list_array][key][]">
                            <label class="control-label" for="itemdescription-list_array-value">Values</label>
                            <input type="text" id="itemdescription-list_array-value" class="form-control"
                                   name="ItemDescription[list_array][value][]">
                        </div>
                        <a id="add-field" class="btn btn-info"><i class="glyphicon glyphicon-plus"></i></a>
                        <a id="minus-field" class="btn btn-info" style="display: none;"><i
                                    class="glyphicon glyphicon-minus"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php ActiveForm::end() ?>




