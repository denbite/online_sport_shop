<?php

use app\components\grid\ActionColumn;
use app\components\grid\DropdownColumn;
use app\components\grid\IdColumn;
use app\components\grid\OwnColumn;
use app\components\helpers\Permission;
use app\components\models\Status;
use app\models\Image;
use kartik\date\DatePicker;
use yii\helpers\Html;

/** @var object $dataProvider */
/** @var object $searchModel */
/** @var array $types */

$this->title = 'Баннеры';

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
            <?php if (Permission::can('admin_banner_create')): ?>
                <?= Html::a('Добавить баннер', [ '/admin/banner/create' ], [ 'class' => 'btn btn-sm
            btn-info', 'style' => 'font-size: 16px;font-weight: 600;margin-left:15px;' ]) ?>
            <?php endif; ?>
        </div>
    </div>
    <div class="box-body no-padding">
        <?php echo \yii\grid\GridView::widget([
            'id' => 'banner-table',
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
                        if (Permission::can('admin_banner_view')) {
                            return Html::a($model->id, [ '/admin/banner/view', 'id' => $model->id ]);
                        }
                        
                        return $model->id;
                    },
                ],
                [
                    'attribute' => 'title',
                ],
                [
                    'attribute' => 'link',
                ],
                [
                    'label' => 'Дата начала - конца',
                    'format' => 'raw',
                    'value' => function ($model)
                    {
                        return date('d:m:Y', $model->publish_from) . ' - ' . date('d:m:Y', $model->publish_to);
                    },
                    'filter' => DatePicker::widget(
                        [
                            'model' => $searchModel,
                            'attribute' => 'date_from',
                            'attribute2' => 'date_to',
                            'type' => DatePicker::TYPE_RANGE,
                            'separator' => '-',
                            'pluginOptions' => [ 'format' => 'dd-mm-yyyy' ],
                        ]
                    ),
                ],
                [
                    'label' => 'Превью',
                    'format' => 'raw',
                    'value' => function ($model)
                    {
                        if (!empty($model->image)) {
                            
                            return Html::img(Image::getLink($model->image->id), [
                                'alt' => $model->image->url,
                                'width' => 50,
                                'height' => 50,
                            ]);
                        }
                        
                        return '';
                    },
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
