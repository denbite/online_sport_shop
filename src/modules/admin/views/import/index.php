<?php

use app\components\grid\ActionColumn;
use app\components\grid\DropdownColumn;
use app\components\grid\IdColumn;
use app\components\grid\OwnColumn;
use app\components\helpers\Permission;
use app\modules\admin\models\Import;
use yii\helpers\Html;

/** @var object $dataProvider */
/** @var object $searchModel */

$this->title = 'Импорт';

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
            <?php if (Permission::can('admin_import_upload-excel')): ?>
                <?= Html::a('Загрузить excel', [ '/admin/import/upload-excel' ], [ 'class' => 'btn btn-sm
            btn-success', 'style' => 'font-size: 16px;font-weight: 600;margin-left:15px;' ]) ?>
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
                                                  'pager' => [
                                                      'class' => 'app\components\widgets\AdminPaginator',
                                                  ],
                                                  'columns' => [
                                                      [
                                                          'attribute' => 'id',
                                                          'class' => IdColumn::className(),
                                                          'label' => '#',
                                                          'format' => 'html',
                                                          'value' => function ($model)
                                                          {
                                                              if (Permission::can('admin_import_view')) {
                                                                  return Html::a($model->id,
                                                                                 [ '/admin/import/view', 'id' => $model->id ]);
                                                              }
                        
                                                              return $model->id;
                                                          },
                                                      ],
                                                      [
                                                          'attribute' => 'type',
                                                          'format' => 'html',
                                                          'class' => DropdownColumn::className(),
                                                          'filter' => Import::getTypes(),
                                                          'css' => Import::getTypesCssClass(),
                                                      ],
                                                      [
                                                          'attribute' => 'user_id',
                                                      ],
                                                      [
                                                          'attribute' => 'params',
                                                          'value' => function ($model)
                                                          {
                                                              return 'name => ' . unserialize($model->params)['name'];
                                                          },
                                                      ],
                                                      [
                                                          'attribute' => 'result',
                                                          'format' => 'html',
                                                          'class' => DropdownColumn::className(),
                                                          'filter' => Import::getResultCodes(),
                                                          'css' => Import::getResultCodesCssClass(),
                                                          'value' => function ($model)
                                                          {
                                                              return (int) unserialize($model->result)['code'];
                                                          },
                
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
