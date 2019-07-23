<?php

use app\assets\AppAsset;
use app\components\helpers\ValueHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;

/** @var string $content */

AppAsset::register($this);

?>

<?php $this->beginPage() ?>

<!doctype html>
<html class="no-js" lang="<?= Yii::$app->language ?>">

<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <?= Html::csrfMetaTags() ?>

    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="/favicon.ico">
    <title><?= Html::encode(!empty($this->title) ? 'Aquista | ' . $this->title : 'Интернет-магазин Aquista | Самая быстрая доставка по Украине') ?></title>
    
    <?php $this->head() ?>

</head>

<body>
<?php $this->beginBody() ?>

<div class="wrapper">
    <?php if (!Yii::$app->user->isGuest and \app\components\helpers\Permission::can('admin_default_index')): ?>
        <div class="row" style="background-color: #000000;">
            <div class="col py-2 px-5">
                <?= Html::a('Добро пожаловать, ' . Yii::$app->user->identity->username, [ '/admin/default/index' ],
                            [ 'class' => 'px-50', 'style' => 'color:#fff;font-size:18px;text-decoration:underline;float:right;' ]) ?>
            </div>
        </div>
    <?php endif; ?>

    <header class="header-area sticky-bar">
        <div class="main-header-wrap">
            <div class="container">
                <div class="row">
                    <div class="col-xl-2 col-lg-2">
                        <div class="logo pt-40">
                            <?= Html::a(Html::img('/files/logo.png', [
                                'width' => 128,
                                'height' => 40,
                            ]), [ '/main/default/index' ]) ?>
                        </div>
                    </div>
                    <div class="col-xl-7 col-lg-7 ">
                        <div class="main-menu">
                            <nav>
                                <ul>
                                    <li class="angle-shape"><a
                                                href="<?= \yii\helpers\Url::to([ '/main/products/category' ]) ?>">
                                            Категории <span>hot</span> </a>
                                        <ul class="mega-menu">
                                            <?php foreach (\app\models\Category::findOne([ 'root' => 1, 'lvl' => 0 ])
                                                                               ->children(1)
                                                                               ->all() as $category): ?>
                                                <li><a class="menu-title"
                                                       href="<?= Url::to([ '/main/products/category', 'slug' => ValueHelper::encryptValue($category['id']) ]) ?>"><?= $category['name'] ?></a>
                                                    <ul>
                                                        <?php foreach ($category->children(1)->all() as $child): ?>
                                                            <li>
                                                                <a href="<?= Url::to([ '/main/products/category', 'slug' => ValueHelper::encryptValue($child['id']) ]) ?>"><?= $child['name'] ?></a>
                                                            </li>
                                                        <?php endforeach; ?>
                                                    </ul>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </li>
                                    <li><a href="<?= Url::to([ '/main/products/catalog' ]) ?>">Каталог <span>hot</span>
                                        </a></li>
                                    <li class="angle-shape"><a href="#">Информация </a>
                                        <ul class="submenu">
                                            <li><a href="<?= Url::to([ '/main/default/delivery' ]) ?>">Доставка </a>
                                            </li>
                                            <li><a href="<?= Url::to([ '/main/default/payment' ]) ?>">Оплата </a></li>
                                            <li><a href="<?= Url::to([ '/main/default/warranty' ]) ?>">Гарантия </a>
                                            </li>
                                            <li><a href="<?= Url::to([ '/main/default/sizes' ]) ?>">Размеры </a></li>
                                            <li><a href="<?= Url::to([ '/main/default/contacts' ]) ?>">Связаться с
                                                    нами</a></li>
                                            <li><a href="<?= Url::to([ '/main/default/about' ]) ?>">О нас </a></li>
                                        </ul>
                                    </li>
                                    <li class="angle-shape"><a
                                                href="<?= Url::to([ '/main/promotions/index' ]) ?>">Акции </a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-3">
                        <div class="header-right-wrap pt-40">
                            <div class="header-search">
                                <a class="search-active" href="#"><i class="sli sli-magnifier"></i></a>
                            </div>
                            <div class="cart-wrap">
                            </div>
                            <div class="setting-wrap">
                                <button class="setting-active">
                                    <i class="sli sli-settings"></i>
                                </button>
                                <div class="setting-content">
                                    <ul>
                                        <li>
                                            <h4>Аккаунт</h4>
                                            <ul>
                                                <?= !Yii::$app->user->isGuest ?
                                                    '<li>' . Html::a("Личный кабинет", [ '/main/profile/index' ]) . '</li>
                                                <li>' . Html::a("Заказы", [ '/main/profile/orders' ]) . '</li>
                                                <li>' . Html::a("Настройки",
                                                        [ '/main/profile/settings' ]) . '</li>' : ''
                                                ?>
                                                <?= Yii::$app->user->isGuest ?
                                                    '<li>' . Html::a('Войти', [ '/user/default/login' ]) . '</li>
                                                        <li>' . Html::a('Регистрация',
                                                        [ '/user/default/signup' ]) . '</li>'
                                                    :
                                                    '<li>' . Html::a('Выход',
                                                        [ '/user/default/logout' ]) . '</li>' ?>
                                            </ul>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- main-search start -->
            <div class="main-search-active">
                <div class="sidebar-search-icon">
                    <button class="search-close"><span class="sli sli-close"></span></button>
                </div>
                <div class="sidebar-search-input">
                    <form>
                        <div class="form-search">
                            <input id="search" class="input-text" value="" placeholder="Search Now" type="search">
                            <button>
                                <i class="sli sli-magnifier"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="header-small-mobile">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-6">
                        <div class="mobile-logo">
                            <?= Html::a(Html::img('/files/logo.png', [
                                'width' => 128,
                                'height' => 40,
                            ]), [ '/main/default/index' ]) ?>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="header-right-wrap">
                            <div class="cart-wrap">
                            </div>
                            <div class="mobile-off-canvas">
                                <a class="mobile-aside-button" href="#"><i class="sli sli-menu"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="mobile-off-canvas-active">
        <a class="mobile-aside-close"><i class="sli sli-close"></i></a>
        <div class="header-mobile-aside-wrap">
            <div class="mobile-search">
                <form class="search-form" action="#">
                    <input type="text" placeholder="Search entire store…">
                    <button class="button-search"><i class="sli sli-magnifier"></i></button>
                </form>
            </div>
            <div class="mobile-menu-wrap">
                <!-- mobile menu start -->
                <div class="mobile-navigation">
                    <!-- mobile menu navigation start -->
                    <nav>
                        <ul class="mobile-menu">
                            <li class="menu-item-has-children "><a
                                        href="<?= \yii\helpers\Url::to([ '/main/products/category' ]) ?>">Категории </a>
                                <ul class="dropdown">
                                    <?php foreach (\app\models\Category::findOne([ 'root' => 1, 'lvl' => 0 ])
                                                                       ->children(1)
                                                                       ->all() as $category): ?>
                                        <li class="menu-item-has-children"><a
                                                    href="<?= Url::to([ '/main/products/category', 'slug' => ValueHelper::encryptValue($category['id']) ]) ?>"><?= $category['name'] ?></a>
                                            <ul class="dropdown">
                                                <?php foreach ($category->children(1)->all() as $child): ?>
                                                    <li>
                                                        <a href="<?= Url::to([ '/main/products/category', 'slug' => ValueHelper::encryptValue($child['id']) ]) ?>"><?= $child['name'] ?></a>
                                                    </li>
                                                <?php endforeach; ?>
                                            </ul>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </li>
                            <li><a href="<?= Url::to([ '/main/products/catalog' ]) ?>">Каталог
                                </a></li>
                            <li class="menu-item-has-children"><a href="#">Информация </a>
                                <ul class="dropdown">
                                    <li><a href="<?= Url::to([ '/main/default/delivery' ]) ?>">Доставка </a>
                                    </li>
                                    <li><a href="<?= Url::to([ '/main/default/payment' ]) ?>">Оплата </a></li>
                                    <li><a href="<?= Url::to([ '/main/default/warranty' ]) ?>">Гарантия </a></li>
                                    <li><a href="<?= Url::to([ '/main/default/sizes' ]) ?>">Размеры </a></li>
                                    <li><a href="<?= Url::to([ '/main/default/contacts' ]) ?>">Связаться с
                                            нами</a></li>
                                    <li><a href="<?= Url::to([ '/main/default/about' ]) ?>">О нас </a></li>
                                </ul>
                            </li>
                            <li><a href="<?= Url::to([ '/main/promotions/index' ]) ?>">Акции</a></li>
                        </ul>
                    </nav>
                    <!-- mobile menu navigation end -->
                </div>
                <!-- mobile menu end -->
            </div>
            <div class="mobile-curr-lang-wrap">
                <div class="single-mobile-curr-lang">
                    <a class="mobile-account-active" href="#">Аккаунт <i class="sli sli-arrow-down"></i></a>
                    <div class="lang-curr-dropdown account-dropdown-active">
                        <ul>
                            <?= !Yii::$app->user->isGuest ?
                                '<li>' . Html::a("Личный кабинет", [ '/main/profile/index' ]) . '</li>
                                                <li>' . Html::a("Заказы", [ '/main/profile/orders' ]) . '</li>
                                                <li>' . Html::a("Настройки",
                                    [ '/main/profile/settings' ]) . '</li>' : ''
                            ?>
                            <?= Yii::$app->user->isGuest ? '<li>' . Html::a('Войти',
                                    [ '/user/default/login' ]) . '</li>
                                                        <li>' . Html::a('Регистрация',
                                    [ '/user/default/signup' ]) . '</li>' :
                                '<li>' . Html::a('Выход',
                                    [ '/user/default/logout' ]) . '</li>' ?>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="mobile-social-wrap">
                <a class="instagram" href="https://instagram.com/aquista7" target="_blank"><i
                            class="sli sli-social-instagram"></i></a>
            </div>
        </div>
    </div>
    
    <?php if (!empty($this->params['breadcrumbs'])): ?>
        <div class="breadcrumb-area pt-35 pb-35 bg-gray">
            <div class="container">
                <div class="breadcrumb-content text-center">
                    <?php
                    
                    echo Breadcrumbs::widget(
                        [
                            'tag' => 'ul',
                            'links' => !empty($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                            'activeItemTemplate' => '<li class="active">{link}</li>',
                            'itemTemplate' => '<li>{link}</li>',
                            'homeLink' => [
                                'label' => 'Главная',
                                'url' => \yii\helpers\Url::to([ '/main/default/index' ]),
                            ],
                            'options' => [
                                'class' => null,
                            ],
                        ]
                    )
                    ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
    
    
    <?= $content ?>
    
    <?php if (Yii::$app->controller->id == 'default'): ?>
        <footer class="footer-area pt-100">
            <div class="container">
                <div class="footer-top-2 pb-20">
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-12 col-12">
                            <div class="footer-widget mb-40">
                                <?= Html::a(Html::img('/files/logo.png', [
                                    'width' => 128,
                                    'height' => 40,
                                ]), [ '/main/default/index' ]) ?>
                                <div class="subscribe-style mt-45">
                                    <p>Подпишитесь на новости, Введите свой e-mail</p>
                                    <div id="mc_embed_signup" class="subscribe-form mt-20">
                                        <form id="mc-embedded-subscribe-form" class="validate subscribe-form-style"
                                              novalidate="" target="_blank" name="mc-embedded-subscribe-form"
                                              method="post"
                                              action="...">
                                            <div id="mc_embed_signup_scroll" class="mc-form">
                                                <input class="email" type="email" required=""
                                                       placeholder="Введите свой e-mail...." name="EMAIL" value="">
                                                <div class="mc-news" aria-hidden="true">
                                                    <input type="text" value="" tabindex="-1"
                                                           name="b_6bbb9b6f5827bd842d9640c82_05d85f18ef">
                                                </div>
                                                <div class="clear">
                                                    <input id="mc-embedded-subscribe" class="button" type="submit"
                                                           name="subscribe" value="">
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-4 col-6">
                            <div class="footer-widget mb-40 pl-100">
                                <div class="footer-title">
                                    <h3>Покупки</h3>
                                </div>
                                <div class="footer-list">
                                    <ul>
                                        <li><a href="<?= Url::to([ '/main/products/catalog' ]) ?>">Каталог</a></li>
                                        <li><a href="<?= Url::to([ '/main/products/category' ]) ?>">Категории</a></li>
                                        <li><a href="<?= Url::to([ '/main/promotions/index' ]) ?>">Акции</a></li>
                                        <li><a href="<?= Url::to([ '/main/products/cart' ]) ?>">Корзина</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-4 col-6">
                            <div class="footer-widget mb-40 pl-50">
                                <div class="footer-title">
                                    <h3>Аккаунт</h3>
                                </div>
                                <div class="footer-list">
                                    <ul>
                                        <?= Yii::$app->user->isGuest ?
                                            '<li>' . Html::a("Войти", Url::to([ "/user/default/login" ])) . '</li>
                                                <li>' . Html::a("Регистрация", Url::to([ "/user/default/signup" ])) . '</li>
                                                <li>' . Html::a("Контакты", Url::to([ "/main/default/contacts" ])) . '</li>
                                                <li>' . Html::a("О нас", Url::to([ "/main/default/about" ])) . '</li>'
                                            :
                                            '<li>' . Html::a("Личный кабинет", Url::to([ "/main/profile/index" ])) . '</li>
                                                <li>' . Html::a("Мои заказы", Url::to([ "/main/profile/orders" ])) . '</li>
                                                <li>' . Html::a("Настройки", Url::to([ "/main/profile/settings" ])) . '</li>
                                                <li>' . Html::a("Выйти", Url::to([ "/user/default/logout" ])) . '</li>'
                                        ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-4 col-12">
                            <div class="footer-widget mb-40">
                                <div class="footer-title">
                                    <h3>Информация</h3>
                                </div>
                                <div class="footer-list">
                                    <ul>
                                        <li><a href="<?= Url::to([ '/main/default/delivery' ]) ?>">Доставка</a></li>
                                        <li><a href="<?= Url::to([ '/main/default/payment' ]) ?>">Оплата</a></li>
                                        <li><a href="<?= Url::to([ '/main/default/warranty' ]) ?>">Гарантия</a></li>
                                        <li><a href="<?= Url::to([ '/main/default/sizes' ]) ?>">Размеры</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="footer-bottom border-top-2 pt-30">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                            <div class="copyright-2 pb-30">
                                <p>Copyright 2018-2019 © All Right Reserved.</p>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                            <div class="payment-mathod-2 pb-30">
                                <a href="#"><img src="/images/main/icon-img/payment.png" alt=""></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
    <?php else: ?>
        <footer class="footer-area bg-paleturquoise">
            <div class="container">
                <div class="footer-top text-center pt-45 pb-45">
                    <nav>
                        <ul>
                            <li><a href="<?= Url::to([ '/main/default/index' ]) ?>">Главная </a></li>
                            <li><a href="<?= Url::to([ '/main/products/catalog' ]) ?>">Каталог </a></li>
                            <li><a href="<?= Url::to([ '/main/products/category' ]) ?>">Категории </a></li>
                            <li><a href="<?= Url::to([ '/main/promotions/index' ]) ?>">Акции </a></li>
                            <li><a href="<?= Url::to([ '/main/default/delivery' ]) ?>">Доставка </a></li>
                            <li><a href="<?= Url::to([ '/main/default/payment' ]) ?>">Оплата </a></li>
                        </ul>
                    </nav>
                </div>
            </div>
            <div class="footer-bottom border-top-1 pt-20">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-lg-4 col-md-5 col-12">
                            <div class="footer-social pb-20">
                                <a href="https://instagram.com/aquista7" target="_blank">Instagram</a>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-12">
                            <div class="copyright text-center pb-20">
                                <p>Copyright 2018-2019 © All Right Reserved</p>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-3 col-12">
                            <div class="payment-mathod pb-20">
                                <a href="#"><img src="/images/main/icon-img/payment.png" alt=""></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
    <?php endif; ?>
</div>

<?php $this->endBody() ?>

</body>

</html>

<?php $this->endPage() ?>
