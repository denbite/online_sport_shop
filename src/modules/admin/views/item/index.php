<?php

use app\components\grid\ActionColumn;
use app\components\grid\DropdownColumn;
use app\components\grid\IdColumn;
use app\components\grid\OwnColumn;
use app\components\helpers\Permission;
use app\components\models\Status;
use yii\helpers\Html;

/** @var object $dataProvider */
/** @var object $searchModel */
/** @var array $category */
/** @var array $categories */

$this->title = 'Товары';

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
        <div class="right-float">
            <?php if (Permission::can('admin_item_create')): ?>
                <?= Html::a('Добавить товар', [ '/admin/item/create' ], [ 'class' => 'btn btn-sm
            btn-info', 'style' => 'font-size: 16px;font-weight: 600;margin-left:15px;' ]) ?>
            <?php endif; ?>

            <?php if (Permission::can('admin_item_create-color')): ?>
                <?= Html::a('Добавить цвет', [
                    '/admin/item/create-color' ], [ 'class' => 'btn btn-sm btn-info', 'style' => 'font-size: 16px;font-weight:
            600;margin-left:15px;' ]) ?>
            <?php endif; ?>

            <?php if (Permission::can('admin_item_create-size')): ?>
                <?= Html::a('Добавить размер', [ '/admin/item/create-size' ], [ 'class' => 'btn
            btn-sm btn-info mt-15 mt-lg-0', 'style' => 'font-size: 16px;font-weight: 600;margin-left:15px;' ]) ?>
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
                                                          'attribute' => 'id',
                                                          'class' => IdColumn::className(),
                                                          'label' => '#',
                                                          'format' => 'html',
                                                          'value' => function($model) {
                                                              if (Permission::can('admin_item_view')) {
                                                                  return Html::a($model->id, [ '/admin/item/view', 'id' => $model->id ]);
                                                              }
                        
                                                              return $model->id;
                                                          },
                                                      ],
                                                      [
                                                          'attribute' => 'category_id',
                                                          'format' => 'html',
                                                          'filter' => $categories,
                                                          'value' => function ($model) use ($category)
                                                          {
                                                              return !empty($category[$model->category_id]) ? $category[$model->category_id]->name : '';
                                                          },
                                                      ],
                                                      [
                                                          'attribute' => 'firm',
    
                                                      ],
                                                      [
                                                          'attribute' => 'model',
                                                      ],
                                                      [
                                                          'attribute' => 'collection',
                
                                                      ],
                                                      [
                                                          'attribute' => 'status',
                                                          'format' => 'html',
                                                          'class' => DropdownColumn::className(),
                                                          'filter' => Status::getStatusesArray(),
                                                          'css' => Status::getStatusCssClass(),
                                                      ],
                                                      [
                                                          'attribute' => 'created_at',
                                                          'format' => 'datetime',
                                                      ],
                                                      [
                                                          'class' => ActionColumn::className(),
                                                      ],
                                                  ],
                                              ])
        
        ?>
    </div>
</div>
