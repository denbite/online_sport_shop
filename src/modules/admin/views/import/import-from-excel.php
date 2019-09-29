<?php

/** @var \app\modules\admin\models\ImportFromExcelForm $model */

/** @var array $categories */

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$this->title = 'Импорт Excel таблицы';
$this->params['breadcrumbs'][] = [ 'label' => 'Импорт', 'url' => [ '/admin/import/index' ] ];
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
                                    'id' => 'import-upload_excel',
                                ]) ?>

    <div class="box box-shadowed box-outline-success <?= !empty(Yii::$app->params['background']) ? Yii::$app->params['background'] : '' ?>">
        <div class="box-header with-border">
            <div class="right-float">
                <?= Html::submitButton(Html::tag('i', '&nbsp;', [ 'class' => 'fas fa-upload' ]) . ' Импорт',
                                       [ 'class' => 'btn btn-sm btn-success' ]) ?>
            </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body form-element">
            <div class="row">
                <div class="col-lg-2 col-6">
                    <?= $form->field($model, 'token')->passwordInput() ?>
                </div>
                <div class="col-lg-2 col-6">
                    <?= $form->field($model, 'from') ?>
                </div>
                <div class="col-lg-2 col-6">
                    <?= $form->field($model, 'to') ?>
                </div>
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
            </div>
        </div>
    </div>
<?php ActiveForm::end() ?>