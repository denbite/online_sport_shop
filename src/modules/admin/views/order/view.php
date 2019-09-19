<?php

use app\components\helpers\Permission;
use app\components\helpers\ValueHelper;
use app\models\Image;
use app\models\Order;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Order */
/* @var $orderSizes array */

$this->title = 'Заказ: №' . $model->id;
$this->params['breadcrumbs'][] = [ 'label' => 'Заказы', 'url' => [ '/admin/order/index' ] ];
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
                <?php if (Permission::can('admin_order_update')): ?>
                    <?= Html::a(Html::tag('i', '&nbsp;', [ 'class' => 'fas fa-pen' ]) . ' Редактировать',
                                [ "/admin/order/update", 'id' => $model->id ],
                                [ 'class' => 'btn btn-sm btn-warning ml-20' ]) ?>
                <?php endif; ?>
                <?php if (Permission::can('admin_order_delete')): ?>
                    <?= Html::a(Html::tag('i', '&nbsp;', [ 'class' => 'fas fa-trash' ]) . ' Удалить',
                                [ "/admin/order/delete", 'id' => $model->id ],
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
                <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#items" role="tab"><span
                                class="hidden-sm-up"><i class="ion-person"></i></span> <span class="hidden-xs-down">Товары</span></a>
                </li>
            </ul>
            <div class="tab-content tabcontent-border">
                <div class="tab-pane active" id="main" role="tabpanel">
                    <div class="pad">
                        <?= DetailView::widget([
                                                   'model' => $model,
                                                   'options' => [
                                                       'class' => 'table table-hover table-bordered detail-view',
                                                       'style' => 'font-weight:600;font-size:16px;',
                                                   ],
                                                   'attributes' => [
                                                       'id',
                                                       'name',
                                                       'phone',
                                                       'email:email',
                                                       [
                                                           'attribute' => 'city',
                                                           'value' => function ($model)
                                                           {
                                                               return Yii::$app->novaposhta->getCityNameByRef($model->city);
                                                           },
                                                       ],
                                                       [
                                                           'attribute' => 'department',
                                                           'value' => function ($model)
                                                           {
                                                               return Yii::$app->novaposhta->getWarehouseNameByRef($model->department);
                                                           },
                                                       ],
                                                       [
                                                           'attribute' => 'invoice',
                                                       ],
                                                       [
                                                           'attribute' => 'sum',
                                                       ],
                                                       'buy_sum',
                                                       [
                                                           'attribute' => 'status',
                                                           'format' => 'html',
                                                           'value' => function ($model)
                                                           {
                                                               $css = Order::getStatusCssClass();
                                                               $filter = Order::getStatuses();
                                        
                                                               if (array_key_exists($model->status,
                                                                                    $css) and array_key_exists($model->status,
                                                                                                               $css)) {
                                                                   return Html::tag('span', $filter[$model->status],
                                                                                    [ 'class' => 'label bg-' . $css[$model->status] ]);
                                                               }
                                        
                                                               return null;
                                                           },
                                                       ],
                                                       [
                                                           'attribute' => 'delivery',
                                                           'value' => function ($model)
                                                           {
                                        
                                                               $deliveries = Order::getDeliveries();
                                        
                                                               if (array_key_exists($model->delivery, $deliveries)) {
                                                                   return $deliveries[$model->delivery]['name'];
                                                               }
                                        
                                                               return null;
                                                           },
                                                       ],
                                                       'comment:ntext',
                                                       [
                                                           'attribute' => 'phone_status',
                                                           'format' => 'html',
                                                           'value' => function ($model)
                                                           {
                                                               $css = Order::getPhoneStatusCssClass();
                                                               $filter = Order::getPhoneStatuses();
                                        
                                                               if (array_key_exists($model->phone_status,
                                                                                    $css) and array_key_exists($model->phone_status,
                                                                                                               $css)) {
                                                                   return Html::tag('span',
                                                                                    $filter[$model->phone_status],
                                                                                    [ 'class' => 'label bg-' . $css[$model->phone_status] ]);
                                                               }
                                        
                                                               return null;
                                                           },
                                                       ],
                                                       'updated_at:datetime',
                                                       'created_at:datetime',
                                                   ],
                                               ]) ?>
                    </div>
                </div>
                <div class="tab-pane" id="items" role="tabpanel">
                    <div class="pad">
                        <h1>Товары</h1>
                        <div class="zoom-gallery m-t-30">
                            <?php foreach ($orderSizes as $orderSize): ?>
                                <h4><?= $orderSize['size']['color']['item']['firm'] . ' ' . $orderSize['size']['color']['item']['model'] ?></h4>
                                <p><?= 'Color: ' . $orderSize['size']['color']['color'] . ' => ' . $orderSize['size']['color']['code'] ?></p>
                                <p><?= 'Size: ' . $orderSize['size']['size'] ?></p>
                                <p><?= 'Quantity: ' . $orderSize['quantity'] . ' x ' . ValueHelper::addCurrency($orderSize['cost']) ?></p>
                                <a href="<?= Image::getLink($orderSize['size']['color']['mainImage']['id']) ?>"
                                   title="<?= $orderSize['size']['color']['color'] . ' ' . $orderSize['size']['color']['code'] ?>"
                                   data-source="<?= \yii\helpers\Url::to([ '/main/products/product', 'slug' => ValueHelper::encryptValue($orderSize['size']['color']['item']['id']) ]) ?>">
                                    <img src="<?= Image::getLink($orderSize['size']['color']['mainImage']['id'],
                                                                 Image::SIZE_MEDIUM) ?>"
                                         width="24.8%" alt="<?= $orderSize['size']['color']['mainImage']['url'] ?>"/>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

