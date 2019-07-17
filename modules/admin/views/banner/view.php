<?php

use app\components\helpers\Permission;
use app\components\models\Status;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Banner */
/* @var $modelImage app\models\Image */

$this->title = $model->title;
$this->params['breadcrumbs'][] = [ 'label' => 'Баннеры', 'url' => [ '/admin/banner/index' ] ];
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
                <?php if (Permission::can('admin_banner_update')): ?>
                    <?= Html::a(Html::tag('i', '&nbsp;', [ 'class' => 'fa fa-pencil' ]) . ' Редактировать',
                        [ "/admin/banner/update", 'id' => $model->id ],
                        [ 'class' => 'btn btn-sm btn-warning ml-20' ]) ?>
                <?php endif; ?>
                <?php if (Permission::can('admin_banner_delete')): ?>
                    <?= Html::a(Html::tag('i', '&nbsp;', [ 'class' => 'fa fa-trash' ]) . ' Удалить',
                        [ "/admin/banner/delete", 'id' => $model->id ],
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
                <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#photo" role="tab"><span
                                class="hidden-sm-up"><i class="ion-email"></i></span> <span class="hidden-xs-down">Фотографии</span></a>
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
                                'title',
                                'description',
                                'link',
                                'link_title',
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
                                        return date('d.m.Y h:m', $model->publish_from) . ' - ' . date('d.m.Y h:m',
                                                $model->publish_to);
                                    },
                                ],
                            ],
                        ])
                        ?>
                    </div>
                </div>
                <div class="tab-pane" id="photo" role="tabpanel">
                    <div class="pad">
                        <?php if (!empty($modelImage)): ?>
                            <div id="carousel-example-generic-captions-5" class="carousel slide"
                                 data-ride="carousel">
                                <!-- Indicators -->
                                <!-- Wrapper for slides -->
                                <div class="carousel-inner" role="listbox">
                                    <div class="carousel-item active">
                                        <?= Html::img(\app\models\Image::getLink($modelImage->id),
                                            [ 'class' => 'img-fluid', 'alt' => 'slide-0' ]) ?>
                                        <div class="carousel-caption">
                                            <h3><?= $modelImage->url ?></h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
