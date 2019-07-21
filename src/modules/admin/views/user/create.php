<?php

/** @var SignupForm $model */

use app\modules\user\models\forms\SignupForm;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$this->title = 'Добавить пользователя';

$this->params['breadcrumbs'][] = [ 'label' => 'Пользователи', 'url' => [ '/admin/user/index' ] ];
$this->params['breadcrumbs'][] = $this->title;

?>
<?php $form = ActiveForm::begin([
    'id' => 'user-create',
]) ?>
<div class="box box-shadowed box-outline-success <?= !empty(Yii::$app->params['background']) ? Yii::$app->params['background'] : '' ?>">
    <div class="box-header with-border">
        <div class="right-float">
            <?= Html::submitButton(Html::tag('i', '&nbsp;', [ 'class' => 'fas fa-save' ]) . ' Сохранить',
                [ 'class' => 'btn btn-sm btn-success' ]) ?>
        </div>
    </div>
    <div class="box-body form-element">

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
    </div>
</div>
<?php ActiveForm::end() ?>
