<?php

/** @var SignupForm $model */

use app\modules\user\models\forms\SignupForm;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$this->title = 'Добавить пользователя';

$this->params['breadcrumbs'][] = [ 'label' => 'Пользователи', 'url' => [ '/admin/user/index' ] ];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="container">
    <div class="box box-shadowed box-outline-success <?= !empty(Yii::$app->params['background']) ? Yii::$app->params['background'] : '' ?>">
        <div class="box-body form-element">
            <?php $form = ActiveForm::begin([
                                                'id' => 'user-create',
                                            ]) ?>
            <div class="row">
                <div class="col">
                    <?= $form->field($model, 'username') ?>
                </div>
                <div class="col">
                    <?= $form->field($model, 'password')->passwordInput() ?>
                </div>
                <div class="col">
                    <?= $form->field($model, 'email') ?>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <?= Html::submitButton('Создать', [ 'class' => 'btn btn-success pull-right' ]) ?>
                </div>
            </div>
            <?php ActiveForm::end() ?>
        </div>
    </div>
</div>
