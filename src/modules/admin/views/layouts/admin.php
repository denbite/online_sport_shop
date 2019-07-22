<?php

/** @var string $content */

use app\components\helpers\Permission;
use app\modules\admin\assets\AdminAsset;
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;

AdminAsset::register($this);

$controller = Yii::$app->controller->id;
$action = Yii::$app->controller->action->id;
?>

<?php $this->beginPage() ?>

<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <?= Html::csrfMetaTags() ?>

    <link rel="icon" href="/favicon.ico">

    <title><?= Html::encode($this->title) ?></title>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <?php $this->head() ?>

</head>
<!-- ADD THE CLASS layout-boxed TO GET A BOXED LAYOUT -->
<body class="hold-transition skin-blue fixed sidebar-mini">
<?php $this->beginBody() ?>
<!-- Site wrapper -->
<div class="wrapper">

    <header class="main-header">
        <!-- Logo -->
        <?= Html::a('<b class="logo-mini">
                <span class="light-logo"><img src="/files/logo-admin-icon.png" width="40px" height="40px" alt="logo"></span>
            </b>
            <span class="logo-lg">
		  <img src="/files/logo-admin.png" alt="logo" class="light-logo">
	  </span>', [ '/main/default/index' ], [ 'class' => 'logo' ]) ?>

        <!-- Header Navbar -->
        <nav class="navbar navbar-static-top">
            <!-- Sidebar toggle button-->
            <div>
                <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                    <span class="sr-only">Toggle navigation</span>
                </a>
            </div>

            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">

                    <li class="search-box">
                        <a class="nav-link hidden-sm-down" href="javascript:void(0)"><i class="mdi mdi-magnify"></i></a>
                        <form class="app-search" style="display: none;">
                            <input type="text" class="form-control" placeholder="Search &amp; enter"> <a
                                    class="srh-btn"><i
                                        class="ti-close"></i></a>
                        </form>
                    </li>

                    <!-- User Account-->
                    <li class="dropdown user user-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <img src="<?= !empty(Yii::$app->user->identity->image) ? Yii::$app->user->identity->image : '/files/default-profile.jpg' ?>"
                                 class="user-image rounded-circle"
                                 alt="User Image">
                        </a>
                        <ul class="dropdown-menu scale-up">
                            <!-- User image -->
                            <li class="user-header">
                                <img src="<?= !empty(Yii::$app->user->identity->image) ? Yii::$app->user->identity->image : '/files/default-profile.jpg' ?>"
                                     class="float-left rounded-circle"
                                     alt="User Image">
                                <p>
                                    <?= Yii::$app->user->identity->username ?>
                                    <small class="mb-5"><?= Yii::$app->user->identity->email ?></small>
                                    <a href="#" class="btn btn-danger btn-sm btn-rounded">Посмотреть профиль</a>
                                </p>
                            </li>
                            <!-- Menu Body -->
                            <li class="user-body">
                                <div class="row no-gutters">
                                    <div class="col-12 text-left">
                                        <a href="user/default/logout"><i class="fas fa-power-off"></i> Logout</a>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </nav>
    </header>

    <!-- Left side column. contains the sidebar -->
    <aside class="main-sidebar">
        <!-- sidebar-->
        <section class="sidebar">

            <!-- sidebar menu-->
            <ul class="sidebar-menu" data-widget="tree">
                <li <?= $controller == 'default' ? ' class="active"' : '""' ?>>
                    <?= Html::a(Html::tag('i', '&nbsp;', [ 'class' => 'fas fa-home' ]) . Html::tag('span', 'Главная'),
                                [ '/admin/default/index' ]) ?>
                </li>
                <li class="header nav-small-cap">Основные</li>
                <?php if (Permission::can([ 'admin_item_index', 'admin_category_index', 'admin_order_index' ])): ?>
                    <li class="treeview <?= in_array($controller, [ 'item', 'item-color', 'item-size', 'category', 'order' ]) ? ' active' : '' ?>">
                        <a href="#">
                            <i class="fas fa-shopping-bag"></i>
                            <span>Магазин</span>
                            <span class="pull-right-container">
              <i class="fa fa-angle-right pull-right"></i>
            </span>
                        </a>
                        <ul class="treeview-menu">
                            <?php if (Permission::can('admin_item_index')): ?>
                                <li <?= in_array($controller,
                                                 [ 'item', 'item-color', 'item-size' ]) ? ' class="active"' : '' ?>><?= Html::a(Html::tag('i',
                                                                                                                                          '&nbsp;',
                                                                                                                                          [ 'class' => 'far fa-circle' ]) . 'Товары',
                                                                                                                                [ '/admin/item/index' ]) ?></li>
                            <?php endif; ?>
                            <?php if (Permission::can('admin_category_index')): ?>
                                <li<?= $controller == 'category' ? ' class="active"' : '' ?>><?= Html::a(Html::tag('i',
                                                                                                                   '&nbsp;',
                                                                                                                   [ 'class' => 'far fa-circle' ]) . 'Категории',
                                                                                                         [ '/admin/category/index' ]) ?></li>                                     <?php endif; ?>
                            <?php if (Permission::can('admin_order_index')): ?>
                                <li<?= $controller == 'order' ? ' class="active"' : '' ?>><?= Html::a(Html::tag('i',
                                                                                                                '&nbsp;',
                                                                                                                [ 'class' => 'far fa-circle' ]) . 'Заказы',
                                                                                                      [ '/admin/order/index' ]) ?></li>
                            <?php endif; ?>
                        </ul>
                    </li>
                <?php endif; ?>
                <?php if (Permission::can([ 'admin_user_index', 'admin_role_index' ])): ?>
                    <li class="treeview <?= in_array($controller, [ 'user', 'role' ]) ? ' active' : '' ?>">
                        <a href="#">
                            <i class="fas fa-users"></i> <span>Пользователи</span>
                            <span class="pull-right-container">
              <i class="fa fa-angle-right pull-right"></i>
            </span>
                        </a>
                        <ul class="treeview-menu">
                            <?php if (Permission::can('admin_user_index')): ?>
                                <li <?= $controller == 'user' ? ' class="active"' : '' ?>><?= Html::a(Html::tag('i',
                                                                                                                '&nbsp;',
                                                                                                                [ 'class' => 'far fa-circle' ]) . 'Список пользователей',
                                                                                                      [ '/admin/user/index' ]) ?></li>
                            <?php endif; ?>
                            <?php if (Permission::can('admin_role_index')): ?>
                                <li <?= $controller == 'role' ? ' class="active"' : '' ?>><?= Html::a(Html::tag('i',
                                                                                                                '&nbsp;',
                                                                                                                [ 'class' => 'far fa-circle' ]) . 'Роли',
                                                                                                      [ '/admin/role/index' ]) ?></li>
                            <?php endif; ?>
                        </ul>
                    </li>
                <?php endif; ?>
                <?php if (Permission::can([ 'admin_promotion_index', 'admin_banner_index' ])): ?>
                    <li class="treeview <?= in_array($controller, [ 'promotion', 'banner' ]) ? ' active' : '' ?>">
                        <a href="#">
                            <i class="fas fa-bars"></i>
                            <span>Контент</span>
                            <span class="pull-right-container">
              <i class="fa fa-angle-right pull-right"></i>
            </span>
                        </a>
                        <ul class="treeview-menu">
                            <?php if (Permission::can('admin_promotion_index')): ?>
                                <li <?= $controller == 'promotion' ? ' class="active"' : '' ?>><?= Html::a(Html::tag('i',
                                                                                                                     '&nbsp;',
                                                                                                                     [ 'class' => 'far fa-circle' ]) . 'Акции',
                                                                                                           [ '/admin/promotion/index' ]) ?></li>

                            <?php endif; ?>
                            <?php if (Permission::can('admin_banner_index')): ?>
                                <li <?= $controller == 'banner' ? ' class="active"' : '' ?>><?= Html::a(Html::tag('i',
                                                                                                                  '&nbsp;',
                                                                                                                  [ 'class' => 'far fa-circle' ]) . 'Баннеры',
                                                                                                        [ '/admin/banner/index' ]) ?></li>
                            <?php endif; ?>
                        </ul>
                    </li>
                <?php endif; ?>
                <li class="header nav-small-cap">Дополнительные</li>
                <?php if (Permission::can([ 'admin_config_index', ])): ?>
                    <li class="treeview <?= in_array($controller, [ 'config', ]) ? ' active' : '' ?>">
                    <a href="#">
                        <i class="fas fa-cogs"></i>
                        <span>Настройки</span>
                        <span class="pull-right-container">
              <i class="fa fa-angle-right pull-right"></i>
            </span>
                    </a>
                    <ul class="treeview-menu">
                        <?php if (Permission::can('admin_config_index')): ?>
                            <li <?= $controller == 'config' ? ' class="active"' : '' ?>><?= Html::a(Html::tag('i',
                                        '&nbsp;',
                                        [ 'class' => 'far fa-circle' ]) . 'Основные',
                                    [ '/admin/config/index' ]) ?></li>
                        <?php endif; ?>
                    </ul>
                </li>
                <?php endif; ?>
            </ul>
        </section>
    </aside>

    <!-- =============================================== -->

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                <?= Yii::$app->view->title ?>
            </h1>
            <?php
            
            echo Breadcrumbs::widget([
                                         'tag' => 'ol',
                                         'links' => !empty($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                                         'activeItemTemplate' => '<li class="breadcrumb-item active">{link}</li>',
                                         'itemTemplate' => '<li class="breadcrumb-item">{link}</li>',
                                         'homeLink' => [
                                             'label' => '<i class="fas fa-home"></i>' . 'Главная',
                                             'url' => '/admin',
                                         ],
                                         'encodeLabels' => false,
                                     ])
            ?>
        </section>

        <!-- Main content -->
        <section class="content">
            <?= $content ?>
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <footer class="main-footer">
        &copy; 2019 All Rights Reserved.
    </footer>
</div>

<?php $this->endBody() ?>
</body>
</html>

<?php $this->endPage() ?>

