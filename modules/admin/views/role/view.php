<?php

use app\components\helpers\Permission;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\AuthItem */

$this->title = $model->name;
$this->params['breadcrumbs'][] = [ 'label' => 'Роли', 'url' => [ '/admin/role/index' ] ];
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

<div class="col-12">
    <div class="box box-shadowed box-outline-success <?= !empty(Yii::$app->params['background']) ?
        Yii::$app->params['background'] : '' ?>">
        <div class="box-header with-border">
            <div class="pull-right">
                <?php if (Permission::can('admin_role_update')): ?>
                    <?= Html::a(Html::tag('i', '&nbsp;', [ 'class' => 'fa fa-pencil' ]) . ' Редактировать',
                                [ "/admin/role/update", 'name' => $model->name ],
                                [ 'class' => 'btn btn-sm btn-warning ml-20' ]) ?>
                <?php endif; ?>
                <?php if (Permission::can('admin_role_delete')): ?>
                    <?= Html::a(Html::tag('i', '&nbsp;', [ 'class' => 'fa fa-trash' ]) . ' Удалить',
                                [ "/admin/role/delete", 'name' => $model->name ],
                                [ 'class' => 'btn btn-sm btn-danger ml-20',
                                    'data' => [
                                        'confirm' => 'Вы уверены, что хотите удалить эту роль',
                                    ],
                                ]) ?>
                <?php endif; ?>
            </div>
        </div>
        <div class="box-body">
            <?php
            
            echo \yii\widgets\DetailView::widget([
                                                     'model' => $model,
                                                     'options' => [
                                                         'class' => 'table table-hover table-bordered detail-view',
                                                         'style' => 'font-weight:600;font-size:16px;',
                                                     ],
                                                     'attributes' => [
                                                         'name',
                                                         'description',
                                                         [
                                                             'attribute' => 'child',
                                                             'label' => 'Роли',
                                                             'value' => function ($model)
                                                             {
                                                                 return implode(', ',
                                                                                array_keys(Yii::$app->authManager->getChildren($model->name)));
                                                             },
                                                         ],
                                                         'created_at:date',
                                                     ],
                                                 ])
            ?>
        </div>
    </div>
</div>
</div>
