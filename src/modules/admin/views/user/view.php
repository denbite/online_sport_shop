<?php

/** @var \yii\db\ActiveRecord $model */

$this->title = 'Пользователь: ' . $model->name;

$this->params['breadcrumbs'][] = [ 'label' => 'Пользователи', 'url' => [ '/admin/user/index' ] ];
$this->params['breadcrumbs'][] = $this->title;

use app\components\helpers\Permission;
use app\modules\user\models\User;
use yii\helpers\Html;

?>

<div class="box box-shadowed box-outline-success <?= !empty(Yii::$app->params['background']) ? Yii::$app->params['background'] : '' ?>">
    
    <?php if (Permission::can('admin_user_update')): ?>
        <div class="box-header with-border">
            <div class="right-float">
                <?= Html::a(Html::tag('i', '&nbsp;', [ 'class' => 'fas fa-pen' ]) . ' Редактировать',
                            [ "/admin/user/update", 'id' => $model->id ], [ 'class' => 'btn btn-xs btn-warning' ]) ?>
            </div>
        </div>
    <?php endif; ?>
    <div class="box-body form-element">
        <?php
        
        echo \yii\widgets\DetailView::widget([
                                                 'model' => $model,
                                                 'options' => [
                                                     'class' => 'table table-hover table-bordered detail-view',
                                                     'style' => 'font-weight:600;font-size:16px;',
                                                 ],
                                                 'attributes' => [
                                                     'id',
                                                     'name',
                                                     'email',
                                                     [
                                                         'label' => 'Роль',
                                                         'value' => function($model) {
                                                             $roles = array_keys(Yii::$app->authManager->getRolesByUser($model->id));
                        
                                                             return !empty($roles) ? implode(', ', $roles) : 'Без прав';
                                                         },
                                                     ],
                                                     [
                                                         'attribute' => 'status',
                                                         'format' => 'html',
                                                         'value' => function($model) {
                                                             $css = User::getStatusCssClass();
                                                             $filter = User::getStatusesArray();
                        
                                                             if (array_key_exists($model->status, $css) and array_key_exists($model->status, $css)) {
                                                                 return Html::tag('span', $filter[$model->status], [ 'class' => 'label label-' . $css[$model->status] ]);
                                                             }
                        
                                                             return null;
                                                         },
                                                     ],
                                                     'created_at:datetime',
                                                 ],
                                             ])
        ?>
    </div>
</div>


