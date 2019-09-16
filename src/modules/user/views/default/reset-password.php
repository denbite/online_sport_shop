<?php

/* @var $this yii\web\View */

/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \app\modules\user\models\LoginForm */

$this->title = 'Сброс пароля';
$this->params['breadcrumbs'][] = $this->title;

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

?>
<div class="login-register-area pt-100 pb-100">
    <div class="container">
        <div class="row">
            <div class="col-lg-7 col-md-12 ml-auto mr-auto">
                <div class="login-register-wrapper">
                    <div class="login-register-tab-list nav">
                        <h4 style="color:#ff3535;"> Сброс пароля </h4>
                    </div>
                    <div class="tab-content">
                        <div class="tab-pane active">
                            <div class="login-form-container">
                                <div class="login-register-form">
                                    <?php $form = ActiveForm::begin([
                                                                        'id' => 'reset-password-form',
                                                                    ]) ?>
                                    <?= $form->field($model, 'password')->passwordInput([
                                                                                            'placeholder' => $model->getAttributeLabel('password'),
                                                                                        ])->label(false) ?>
                                    
                                    <?= $form->field($model, 'password_repeat')->passwordInput([
                                                                                                   'placeholder' => $model->getAttributeLabel('password_repeat'),
                                                                                               ])->label(false) ?>
                                    <div class="button-box">
                                        <?= Html::submitButton('Изменить пароль') ?>
                                    </div>
                                    <?php ActiveForm::end() ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>