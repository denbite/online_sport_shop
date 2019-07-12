<?php

use app\components\grid\OwnColumn;
use app\components\helpers\Permission;
use yii\helpers\Html;

/** @var object $dataProvider */
/** @var object $searchModel */
/** @var array $category */
/** @var array $categories */

$this->title = 'Роли';

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

<div class="box box-shadowed box-outline-success">
    <div class="box-header with-border">
        <div class="pull-right">
            <?php if (Permission::can('admin_role_create')): ?>
                <?= Html::a('Добавить роль', [ '/admin/role/create' ], [ 'class' => 'btn btn-sm
            btn-info', 'style' => 'font-size: 16px;font-weight: 600;margin-left:15px;' ]) ?>
            <?php endif; ?>
        </div>
    </div>
    <div class="box-body no-padding">
        <?php echo \yii\grid\GridView::widget([
            'id' => 'item-table',
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'tableOptions' => [
                'class' => 'table table-hover ' . ( !empty(Yii::$app->params['background']) ? Yii::$app->params['background'] : '' ),
            ],
            'options' => [
                'class' => 'table-responsive',
            
            ],
            'summary' => '<div class="box-header with-border"><h4 class="box-title">Найдено {totalCount} шт., показывается {begin} - {end}</h4></div>',
            'dataColumnClass' => OwnColumn::className(),
            'columns' => [
                [
                    'attribute' => 'name',
                
                ],
                [
                    'attribute' => 'description',
                
                ],
                [
                    'attribute' => 'created_at',
                    'format' => 'datetime',
                ],
                [
                    'class' => \yii\grid\ActionColumn::className(),
                    'template' => '{update}',
                    'buttons' => [
                        'update' => function ($url, $model)
                        {
                            return Html::a(Html::tag('i', '&nbsp;', [ 'class' => 'fa fa-pencil' ]) . ' Редактировать',
                                [ "/admin/role/update", 'name' => $model->name ],
                                [ 'class' => 'btn btn-sm btn-warning' ]);
                        },
                    ],
                ],
            ],
        ])
        
        ?>
    </div>
</div>
