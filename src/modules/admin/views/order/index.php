<?php

use app\components\grid\DropdownColumn;
use app\components\grid\IdColumn;
use app\components\grid\OwnColumn;
use app\components\helpers\Permission;
use app\components\models\Status;
use app\models\Order;
use yii\grid\GridView;
use yii\helpers\Html;

/** @var object $dataProvider */
/** @var object $searchModel */

$this->title = 'Заказы';

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
    <div class="box-body no-padding">
        <?= GridView::widget([
                                 'id' => 'order-table',
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
                                         'value' => function ($model)
                                         {
                                             if (Permission::can('admin_order_view')) {
                                                 return Html::a($model->id,
                                                                [ '/admin/order/view', 'id' => $model->id ]);
                                             }
                        
                                             return $model->id;
                                         },
                                     ],
                                     //                                     'user_id',
                                     [
                                         'attribute' => 'name',
                                     ],
                                     [
                                         'attribute' => 'phone',
                                     ],
                                     [
                                         'attribute' => 'city',
                                         'value' => function ($model)
                                         {
                                             return Yii::$app->novaposhta->getCityNameByRef($model->city, 'ru');
                                         },
                                     ],
                                     [
                                         'attribute' => 'department',
                                         'contentOptions' => [
                                             'style' => 'max-width: 120px; white-space: normal;font-weight: 600; font-size: 16px;text-align:center;',
                                         ],
                                         'headerOptions' => [
                                             'style' => 'max-width: 120px; white-space: normal;font-weight: 600; font-size: 16px;text-align:center;',
                                         ],
                                         //                                         'filterOptions' => [
                                         //                                             'style' => 'max-width: 150px; white-space: normal; font-weight: 600; font-size: 16px;text-align:center;',
                                         //                                         ],
                                         //                                         'filterInputOptions' => [
                                         //                                             'style' => 'max-width: 150px; white-space: normal; font-weight: 600; font-size: 16px;text-align:center;',
                                         //                                         ],
                                         'value' => function ($model)
                                         {
                                             return Yii::$app->novaposhta->getWarehouseNameByRef($model->department,
                                                                                                 'ru');
                                         },
                                     ],
                                     //'invoice',
                                     //'sum',
                                     //'buy_sum',
                                     [
                                         'attribute' => 'status',
                                         'format' => 'html',
                                         'class' => DropdownColumn::className(),
                                         'filter' => Order::getStatuses(),
                                         'css' => Status::getStatusCssClass(),
                                     ],
                                     //'delivery',
                                     //'comment:ntext',
                                     [
                                         'attribute' => 'phone_status',
                                         'format' => 'html',
                                         'class' => DropdownColumn::className(),
                                         'filter' => Order::getPhoneStatuses(),
                                         'css' => Status::getStatusCssClass(),
                                     ],
                                     //'updated_at',
                                     //'created_at',
                
                                     //                                     [
                                     //                                         'class' => ActionColumn::className(),
                                     //                                     ],
                                 ],
                             ]); ?>
    </div>
</div>