<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $params array */

$this->title = 'Настройки';
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
                                    'id' => 'config',
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
        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">
            <?php foreach (array_keys($params) as $index => $tab) : ?>
                <li class="nav-item"><a class="nav-link <?= !$index ? ' active' : '' ?>" data-toggle="tab"
                                        href="#<?= $index ?>" role="tab"><span
                                class="hidden-sm-up"><i class="ion-home"></i></span> <span
                                class="hidden-xs-down"><?= $tab ?></span></a></li>
            <?php endforeach; ?>
        </ul>
        <!-- Tab panes -->
        <div class="tab-content tabcontent-border">
            <?php foreach (array_keys($params) as $index => $tab) : ?>
                <div class="tab-pane <?= !$index ? ' active' : '' ?>" id="<?= $index ?>" role="tabpanel">
                    <div class="pad">
                        <?php foreach ($params[$tab] as $attr => $model): ?>
                            <div class="row">
                                <div class="col-lg-6 col-12 py-3">
                                    <?= $form->field($model, '[' . $attr . ']value')->label($model->label) ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <!-- /.box-body -->
</div>
<!-- /.box -->

<?php ActiveForm::end() ?>

