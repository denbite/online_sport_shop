<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Order */

$this->title = 'Заказ: №' . $model->id;
$this->params['breadcrumbs'][] = [ 'label' => 'Orders', 'url' => [ 'index' ] ];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="order-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', [ 'update', 'id' => $model->id ], [ 'class' => 'btn btn-primary' ]) ?>
        <?= Html::a('Delete', [ 'delete', 'id' => $model->id ], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>
    
    <?= DetailView::widget([
                               'model' => $model,
                               'attributes' => [
                                   'id',
                                   'user_id',
                                   'name',
                                   'phone',
                                   'email:email',
                                   'city',
                                   'department',
                                   'invoice',
                                   'sum',
                                   'buy_sum',
                                   'status',
                                   'delivery',
                                   'comment:ntext',
                                   'phone_status',
                                   'updated_at',
                                   'created_at',
                               ],
                           ]) ?>

</div>
