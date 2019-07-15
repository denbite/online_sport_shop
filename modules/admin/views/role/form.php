<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\AuthItem */
/* @var $modelChild app\models\AuthItemChild */
/* @var $permissions array */

$this->title = $model->isNewRecord ? 'Создание роли' : 'Редактирование роли: ' . $model->name;
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
    'id' => $model->isNewRecord ? 'role-create' : 'role-update',
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
                <?= $form->field($model, 'name') ?>
            </div>
            <div class="col-lg-2 col-6">
                <?= $form->field($model, 'description') ?>
            </div>
        </div>
        <div class="myadmin-dd dd" id="nestable">
            <ol class="dd-list">
                <?php foreach ($permissions as $group => $permission): ?>
                    <li class="dd-item" data-id="<?= $group ?>">
                        <div class="dd-handle"> <?= $group ?> </div>
                        <ol class="dd-list">
                            <?php foreach ($permission as $rule): ?>
                                <li class="dd-item" data-id="<?= $rule ?>">
                                    <div class="dd-handle">
                                        <div class="checkbox">
                                            <input type="checkbox" id="checkbox_<?= $rule ?>"
                                                   name="AuthItemChild[<?= $rule ?>]" <?= !empty($modelChild[$rule]) ? ' checked' : '' ?>>
                                            <label for="checkbox_<?= $rule ?>"><?= $rule ?></label>
                                        </div>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        </ol>
                    </li>
                <?php endforeach; ?>
            </ol>
        </div>
    </div>
</div>
<?php ActiveForm::end() ?>



