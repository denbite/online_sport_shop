<?php

use app\components\grid\ActionColumn;
use app\components\grid\DropdownColumn;
use app\components\grid\IdColumn;
use app\components\grid\OwnColumn;
use app\components\helpers\Permission;
use app\modules\user\models\User;
use yii\helpers\Html;

/** @var object $dataProvider */
/** @var object $searchModel */

$this->title = 'Пользователи';

$this->params['breadcrumbs'][] = $this->title;
?>

<div class="box box-shadowed box-outline-success">

    <div class="box-header with-border">
        <div class="right-float">
            <?= Html::a('Добавить пользователя', [ '/admin/user/create' ], [ 'class' => 'btn-sm btn-info', 'style' => 'font-size: 16px;font-weight: 600;' ]) ?>
        </div>
    </div>

    <div class="box-body no-padding">
        <?php echo \yii\grid\GridView::widget([
                                                  'id' => 'user-table',
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
                                                              if (Permission::can('admin_user_view')) {
                                                                  return Html::a($model->id, [ '/admin/user/view', 'id' => $model->id ]);
                                                              }
                        
                                                              return $model->id;
                                                          },
                                                      ],
                                                      [
                                                          'attribute' => 'name',
                
                                                      ],
                                                      [
                                                          'attribute' => 'email',
                
                                                      ],
                                                      [
                                                          'attribute' => 'role',
                                                          'label' => 'Роли',
                                                          'format' => 'html',
                                                          'filter' => Permission::getAllRoles(),
                                                          'value' => function($model) {
                                                              $roles = array_keys(Yii::$app->authManager->getRolesByUser($model->id));
                        
                                                              return !empty($roles) ? implode(', ', $roles) : null;
                                                          },
                                                      ],
                                                      [
                                                          'attribute' => 'status',
                                                          'format' => 'html',
                                                          'class' => DropdownColumn::className(),
                                                          'filter' => User::getStatusesArray(),
                                                          'css' => User::getStatusCssClass(),
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
