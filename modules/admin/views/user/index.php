<?php

use app\components\grid\DropdownColumn;
use app\components\grid\IdColumn;
use app\components\grid\OwnColumn;
use app\components\grid\StatusColumn;
use app\components\helpers\Permission;
use app\modules\user\models\User;
use yii\helpers\Html;

/** @var object $dataProvider */
/** @var object $searchModel */

$this->title = 'Пользователи';

$this->params['breadcrumbs'][] = $this->title;
?>

<div class="box box-shadowed">

    <div class="box-body no-padding">
        <?php echo \yii\grid\GridView::widget([
                                                  'id' => 'user-table',
                                                  'dataProvider' => $dataProvider,
                                                  'filterModel' => $searchModel,
                                                  'tableOptions' => [
                                                      'class' => 'table table-hover bg-food-white',
                                                  ],
                                                  'options' => [
                                                      'class' => 'table-responsive',
                                                  ],
                                                  'summary' => '<div class="box-header with-border"><h4 class="box-title">Найдено {totalCount} шт., показывается {begin} - {end}</h4><div class="box-controls pull-right">' . Html::a('Добавить пользователя', [ '/admin/user/create' ], [ 'class' => 'btn btn-xs btn-info', 'style' => 'font-size: 16px;font-weight: 600;' ]) . '</div></div>',
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
                                                          'attribute' => 'username',
                
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
                                                  ],
                                              ])
        
        ?>
    </div>
</div>
