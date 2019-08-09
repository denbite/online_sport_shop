<?php

use app\components\helpers\Permission;
use app\components\models\Status;
use kartik\select2\Select2;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Promotion */
/* @var $types array */
/* @var $items array */

$this->title = 'Акция: ' . $model->id;
$this->params['breadcrumbs'][] = [ 'label' => 'Акции', 'url' => [ 'index' ] ];
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
                <?php if (Permission::can('admin_promotion_update')): ?>
                    <?= Html::a(Html::tag('i', '&nbsp;', [ 'class' => 'fas fa-pen' ]) . ' Редактировать',
                                [ "/admin/promotion/update", 'id' => $model->id ],
                                [ 'class' => 'btn btn-sm btn-warning ml-20' ]) ?>
                <?php endif; ?>
                <?php if (Permission::can('admin_promotion_delete')): ?>
                    <?= Html::a(Html::tag('i', '&nbsp;', [ 'class' => 'fas fa-trash' ]) . ' Удалить',
                                [ "/admin/promotion/delete", 'id' => $model->id ],
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
                <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#item" role="tab"><span
                                class="hidden-sm-up"><i class="ion-email"></i></span> <span class="hidden-xs-down">Товары</span></a>
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
                                    'attribute' => 'type',
                                    'value' => function ($model) use ($types)
                                    {
                                        return !empty($types[$model->type]) ? $types[$model->type] : '';
                                    },
                                ],
                                'sale',
                                [
                                    'attribute' => 'status',
                                    'format' => 'html',
                                    'value' => function ($model)
                                    {
                                        $css = Status::getStatusCssClass();
                                        $filter = Status::getStatusesArray();
                                        
                                        if (array_key_exists($model->status, $css) and array_key_exists($model->status,
                                                $css)) {
                                            return Html::tag('span', $filter[$model->status],
                                                [ 'class' => 'label label-' . $css[$model->status] ]);
                                        }
                                        
                                        return null;
                                    },
                                ],
                                [
                                    'label' => 'Время показа',
                                    'value' => function ($model)
                                    {
                                        return date('d.m.Y H:i', $model->publish_from) . ' - ' . date('d.m.Y H:i',
                                                $model->publish_to);
                                    },
                                ],
                                'created_at:date',
                            ],
                        ])
                        ?>
                    </div>
                </div>
                <div class="tab-pane" id="item" role="tabpanel">
                    <div class="pad">
                        <div class="row">
                            <div class="col-12">
                                <?= Select2::widget([
                                    'name' => 'ItemColorSize',
                                    'data' => $items,
                                    'value' => !empty($current) ? $current : [],
                                    'options' => [
                                        'multiple' => true,
                                        'placeholder' => 'Выберите нужные размеры',
                                        'disabled' => true,
                                    ],
                                    'pluginOptions' => [
                                        'allowClear' => true,
                                    ],
                                ]) ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>