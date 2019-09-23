<?php

namespace app\modules\admin\controllers;

use app\models\ItemColorSize;
use app\models\Order;
use app\models\OrderSize;
use app\modules\admin\models\OrderSearch;
use Yii;
use yii\filters\VerbFilter;
use yii\helpers\Html;
use yii\web\Controller;
use yii\web\MethodNotAllowedHttpException;
use yii\web\NotFoundHttpException;

/**
 * OrderController implements the CRUD actions for Order model.
 */
class OrderController
    extends Controller
{
    
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => [ 'POST' ],
                ],
            ],
        ];
    }
    
    /**
     * Lists all Order models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new OrderSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    
    /**
     * Displays a single Order model.
     *
     * @param integer $id
     *
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        
        $orderSizes = OrderSize::find()
                               ->with([ 'size.color.mainImage', 'size.color.item' ])
                               ->where([
                                           'order_id' => $id,
                                       ])
                               ->asArray()
                               ->all();
        
        return $this->render('view', [
            'model' => $this->findModel($id),
            'orderSizes' => $orderSizes,
        ]);
    }
    
    public function actionEditable($redirect = false)
    {
        if (Yii::$app->request->isPost and $post = Yii::$app->request->post()) {
            
            $result = [ 'success' => false ];
            
            if (array_key_exists('name', $post) and array_key_exists('value', $post) and array_key_exists('pk',
                                                                                                          $post)) {
                $name = Html::encode($post['name']);
                $value = Html::encode($post['value']);
                $id = Html::encode($post['pk']);
                
                if ($model = Order::findOne([ 'id' => $id ])) {
                    $model->$name = $value;
                    if ($model->save()) {
                        if ($name == 'status' and $value == Order::ORDER_STATUS_CANCELED) {
                            $items = OrderSize::find()->where([ 'order_id' => $id ])->asArray()->all();
                            
                            foreach ($items as $item) {
                                ItemColorSize::updateAllCounters([ 'quantity' => $item['quantity'] ],
                                                                 [ 'id' => $item['size_id'] ]);
                            }
                        }
                        $result['success'] = true;
                    } else {
                        $result['msg'] = 'Не удалось сохранить изменения';
                    }
                } else {
                    $result['msg'] = 'Не удалось найти заказ с таким id: ' . $id;
                }
                
            } else {
                $result['msg'] = 'Некоторые параметры не были переданы';
            }
            
            return ( $redirect and $result['success'] ) ?
                $this->redirect([ '/admin/order/view', 'id' => $id ]) :
                $this->asJson($result);
        }
        
        throw new MethodNotAllowedHttpException('Only POST method allowes');
    }
    
    /**
     * Finds the Order model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id
     *
     * @return Order the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (( $model = Order::findOne($id) ) !== null) {
            return $model;
        }
        
        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
