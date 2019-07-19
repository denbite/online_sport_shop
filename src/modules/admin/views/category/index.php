<?php

use app\components\helpers\Permission;
use kartik\tree\TreeView;

/* @var $this yii\web\View */
/* @var $query \app\models\Category */

$this->title = 'Категории';
$this->params['breadcrumbs'][] = $this->title;
?>

<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css">
<script defer src="https://use.fontawesome.com/releases/v5.3.1/js/all.js" crossorigin="anonymous"></script>

<div class="box box-shadowed box-outline-success">
    <div class="box-body no-padding">
        <?php
        
        echo TreeView::widget([
                                  // single query fetch to render the tree
                                  // use the Product model you have in the previous step
                                  'query' => $query,
                                  'headingOptions' => [ 'label' => $this->title ],
            'fontAwesome' => false,     // optional
                                  'isAdmin' => Permission::can('admin_category_admin') ? true : false,         // optional (toggle to enable admin mode)
                                  'displayValue' => 1,        // initial display value
                                  'softDelete' => false,       // defaults to true
                                  'cacheSettings' => [
                                      // todo-cache: add cache, softDelete to true!
                                      'enableCache' => false   // defaults to true
                                  ],
                                  'nodeAddlViews' => [
                                      \kartik\tree\Module::VIEW_PART_2 => '@app/modules/admin/views/category/_treePart2',
                                  ],
                                  'showInactive' => true, // show all nodes
                              ]);
        ?>
    </div>
</div>