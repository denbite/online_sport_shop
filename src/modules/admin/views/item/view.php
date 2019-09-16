<?php

use app\components\helpers\Permission;
use app\components\models\Status;
use app\models\Category;
use app\models\Image;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Item */
/* @var $modelColors app\models\ItemColor */
/* @var $modelSizes app\models\ItemColorSize */
/* @var $modelImages app\models\Image */
/* @var $modelDescription app\models\ItemDescription */

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
            <div class="right-float">
                <?php if (Permission::can('admin_item_update')): ?>
                    <?= Html::a(Html::tag('i', '&nbsp;', [ 'class' => 'fas fa-pen' ]) . ' Редактировать',
                                [ "/admin/item/update", 'id' => $model->id ],
                                [ 'class' => 'btn btn-sm btn-warning ml-20' ]) ?>
                <?php endif; ?>
                <?php if (Permission::can('admin_item_delete')): ?>
                    <?= Html::a(Html::tag('i', '&nbsp;', [ 'class' => 'fas fa-trash' ]) . ' Удалить',
                                [ "/admin/item/delete", 'id' => $model->id ],
                                [ 'class' => 'btn btn-sm btn-danger ml-20', 'data' => [
                                    'confirm' => 'Вы уверены, что хотите удалить этот товар',
                                ], ]) ?>
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
                                                                     'rate',
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
                                                                         'html',
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
                        <?php foreach ($modelColors as $modelColor): ?>
                            <h1><?= $modelColor->color ?></h1>
                            <?php foreach ($modelSizes[$modelColor->id] as $modelSize): ?>
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
                                                                             'base_price',
                                                                             'sell_price',
                                                                             [
                                                                                 'attribute' => 'sale_price',
                                                                                 'value' => function ($model)
                                                                                 {
                                                                                     return !empty($model->promotion) ? $model->sell_price : 0;
                                                                                 },
                                                                             ],
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
                        <?php foreach ($modelColors as $modelColor): ?>
                            <h1><?= $modelColor->color ?></h1>
                            <div class="zoom-gallery m-t-30">
                                <?php foreach ($modelColor->allImages as $image): ?>
                                    <a href="<?= Image::getLink($image->id) ?>"
                                       title="<?= $image->url ?>"
                                       data-source="<?= Image::getLink($image->id) ?>">
                                        <img src="<?= Image::getLink($image->id, Image::SIZE_MEDIUM) ?>"
                                             width="24.8%" alt="<?= $image->url ?>"/>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="tab-pane" id="description" role="tabpanel">
                    <div class="pad">
                        <?php
    
                        echo \yii\widgets\DetailView::widget([
                            'model' => $modelDescription,
                            'options' => [
                                'class' => 'table table-hover table-bordered detail-view',
                                'style' => 'font-weight:600;font-size:16px;',
                            ],
                            'attributes' => [
                                'small_text',
                                'small_list',
                                'text',
                                'list',
                                'created_at:date',
                            ],
                        ])
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
