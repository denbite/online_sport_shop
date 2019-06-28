<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \app\modules\user\models\LoginForm */

$this->title = 'Вход';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-default-login">
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin([ 'id' => 'login-form' ]); ?>
            <?php if ($model->scenario === 'loginWithEmail') : ?>
                <?= $form->field($model, 'email') ?>
            <?php else : ?>
                <?= $form->field($model, 'username') ?>
            <?php endif; ?>
            <?= $form->field($model, 'password')->passwordInput() ?>
            <?= $form->field($model, 'rememberMe')->checkbox() ?>
            <div style="color:#999;margin:1em 0"><?= Html::a('Сбросить пароль', [ 'password-reset-request' ]) ?></div>
            <div class="form-group">
                <?= Html::submitButton('Войти', [ 'class' => 'btn btn-primary', 'name' => 'login-button' ]) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
