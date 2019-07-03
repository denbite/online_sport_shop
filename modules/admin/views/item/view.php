<?php

use app\components\helpers\Permission;
use app\components\models\Status;
use app\models\Category;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Item */
/* @var $modelColors app\models\ItemColor */
/* @var $modelColorsSizes app\models\ItemColorSize */

$this->title = $model->firm . ' ' . $model->model;
$this->params['breadcrumbs'][] = [ 'label' => 'Товары', 'url' => [ '/admin/item/index' ] ];
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
                <?php if (Permission::can('admin_item_update')): ?>
                    <?= Html::a(Html::tag('i', '&nbsp;', [ 'class' => 'fa fa-pencil' ]) . ' Редактировать',
                        [ "/admin/item/update", 'id' => $model->id ], [ 'class' => 'btn btn-sm btn-warning ml-20' ]) ?>
                <?php endif; ?>
                <?php if (Permission::can('admin_item_delete')): ?>
                    <?= Html::a(Html::tag('i', '&nbsp;', [ 'class' => 'fa fa-pencil' ]) . ' Удалить',
                        [ "/admin/item/delete", 'id' => $model->id ], [ 'class' => 'btn btn-sm btn-danger ml-20' ]) ?>
                <?php endif; ?>
            </div>
        </div>
        <div class="box-body">
            <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#main" role="tab"><span
                                class="hidden-sm-up"><i class="ion-home"></i></span> <span
                                class="hidden-xs-down">Основные</span></a></li>
                <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#color" role="tab"><span
                                class="hidden-sm-up"><i class="ion-person"></i></span> <span class="hidden-xs-down">Цвета</span></a>
                </li>
                <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#size" role="tab"><span
                                class="hidden-sm-up"><i class="ion-email"></i></span> <span class="hidden-xs-down">Размеры</span></a>
                </li>
                <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#photo" role="tab"><span
                                class="hidden-sm-up"><i class="ion-email"></i></span> <span class="hidden-xs-down">Фотографии</span></a>
                </li>
                <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#description" role="tab"><span
                                class="hidden-sm-up"><i class="ion-email"></i></span> <span class="hidden-xs-down">Описание</span></a>
                </li>
            </ul>
            <div class="tab-content tabcontent-border">
                <div class="tab-pane active" id="main" role="tabpanel">
                    <div class="pad">
                        <?php
                        
                        echo \yii\widgets\DetailView::widget([
                                                                 'model' => $model,
                                                                 'options' => [
                                                                     'class' => 'table table-hover table-bordered detail-view',
                                                                     'style' => 'font-weight:600;font-size:16px;',
                                                                 ],
                                                                 'attributes' => [
                                                                     'id',
                                                                     [
                                                                         'attribute' => 'category_id',
                                                                         'value' => function($model) {
                                                                             $category = Category::getAllCategoriesByRoot();
    
                                                                             return !empty($category[$model->category_id]) ? $category[$model->category_id]->name : '';
                                                                         },
                                                                     ],
                                                                     'firm',
                                                                     'model',
                                                                     'collection',
                                                                     'code',
                                                                     [
                                                                         'attribute' => 'status',
                                                                         'format' => 'html',
                                                                         'value' => function($model) {
                                                                             $css = Status::getStatusCssClass();
                                                                             $filter = Status::getStatusesArray();
                                        
                                                                             if (array_key_exists($model->status, $css) and array_key_exists($model->status, $css)) {
                                                                                 return Html::tag('span', $filter[$model->status], [ 'class' => 'label label-' . $css[$model->status] ]);
                                                                             }
                                        
                                                                             return null;
                                                                         },
                                                                     ],
                                                                     'created_at:date',
                                                                 ],
                                                             ])
                        ?>
                    </div>
                </div>
                <div class="tab-pane" id="color" role="tabpanel">
                    <div class="pad">
                        <?php foreach ($modelColors as $modelColor): ?>
                            <?php
                            
                            echo \yii\widgets\DetailView::widget([
                                                                     'model' => $modelColor,
                                                                     'options' => [
                                                                         'class' => 'table table-hover table-bordered detail-view',
                                                                         'style' => 'font-weight:600;font-size:16px;',
                                                                     ],
                                                                     'attributes' => [
                                                                         [
                                                                             'attribute' => 'id',
                                                                             'contentOptions' => [
                                                                                 'style' => 'width:45%;',
                                                                             ],
                                                                         ],
                                                                         'code',
                                                                         'color',
                                                                         [
                                                                             'attribute' => 'status',
                                                                             'format' => 'html',
                                                                             'value' => function($model) {
                                                                                 $css = Status::getStatusCssClass();
                                                                                 $filter = Status::getStatusesArray();
                                            
                                                                                 if (array_key_exists($model->status, $css) and array_key_exists($model->status, $css)) {
                                                                                     return Html::tag('span', $filter[$model->status], [ 'class' => 'label label-' . $css[$model->status] ]);
                                                                                 }
                                            
                                                                                 return null;
                                                                             },
                                                                         ],
                                                                     ],
                                                                 ])
                            ?>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="tab-pane" id="size" role="tabpanel">
                    <div class="pad">
                        <?php foreach ($modelColorsSizes as $color => $modelColorSize): ?>
                            <h1><?= $color ?></h1>
                            <?php foreach ($modelColorSize as $modelSize): ?>
                                <?php
                                
                                echo \yii\widgets\DetailView::widget([
                                                                         'model' => $modelSize,
                                                                         'options' => [
                                                                             'class' => 'table table-hover table-bordered detail-view',
                                                                             'style' => 'font-weight:600;font-size:16px;',
                                                                         ],
                                                                         'attributes' => [
                                                                             [
                                                                                 'attribute' => 'id',
                                                                                 'contentOptions' => [
                                                                                     'style' => 'width:45%;',
                                                                                 ],
                                                                             ],
                                                                             'size',
                                                                             'quantity',
                                                                             'price',
                                                                             [
                                                                                 'attribute' => 'status',
                                                                                 'format' => 'html',
                                                                                 'value' => function($model) {
                                                                                     $css = Status::getStatusCssClass();
                                                                                     $filter = Status::getStatusesArray();
                                                
                                                                                     if (array_key_exists($model->status, $css) and array_key_exists($model->status, $css)) {
                                                                                         return Html::tag('span', $filter[$model->status], [ 'class' => 'label label-' . $css[$model->status] ]);
                                                                                     }
                                                
                                                                                     return null;
                                                                                 },
                                                                             ],
                                                                         ],
                                                                     ])
                                ?>
                            <?php endforeach; ?>
                        
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="tab-pane" id="photo" role="tabpanel">
                    <div class="pad">
                        <h1>Coming Soon!</h1>
                    </div>
                </div>
                <div class="tab-pane" id="description" role="tabpanel">
                    <div class="pad">
                        <h1>Coming Soon!</h1>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
