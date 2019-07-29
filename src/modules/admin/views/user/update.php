<?php

/** @var \yii\db\ActiveRecord $model */

$this->title = 'Пользователь: ' . $model->name;

$this->params['breadcrumbs'][] = [ 'label' => 'Пользователи', 'url' => [ '/admin/user/index' ] ];
$this->params['breadcrumbs'][] = $this->title;

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

?>

<?php $form = ActiveForm::begin([
                                    'id' => 'user-update',
                                ]) ?>
<div class="box box-shadowed box-outline-success <?= !empty(Yii::$app->params['background']) ? Yii::$app->params['background'] : '' ?>">
    <div class="box-header with-border">
        <div class="right-float">
            <?= Html::submitButton(Html::tag('i', '&nbsp;', [ 'class' => 'fas fa-save' ]) . ' Сохранить',
                [ 'class' => 'btn btn-sm btn-success' ]) ?>
        </div>
    </div>
    <div class="box-body">
        <div class="row">
            <div class="col">
                <?= $form->field($model, 'name') ?>
            </div>
            <div class="col">
                <?= $form->field($model, 'email') ?>
            </div>
            <div class="col">
                <?= $form->field($model, 'status')
                         ->dropDownList($statuses) ?>
            </div>
            <div class="col">
                <div class="form-group">
                    <?php foreach ($roles as $role): ?>
                        <div class="checkbox">
                            <input type="checkbox" id="checkbox_<?= $role ?>"
                                   name="User[roles][<?= $role ?>]" <?= array_key_exists($role, $user_roles) ? ' checked' : '' ?>>
                            <label for="checkbox_<?= $role ?>"><?= $role ?></label>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php ActiveForm::end() ?>


