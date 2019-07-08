<?php

use app\assets\AppAsset;
use yii\helpers\Html;
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
    <link rel="shortcut icon" type="image/x-icon" href="images/main/favicon.png">
    <title><?= Html::encode($this->title) ?></title>
    
    <?php $this->head() ?>

</head>

<body>
<?php $this->beginBody() ?>

<div class="wrapper">
    <?php if (!Yii::$app->user->isGuest and \app\components\helpers\Permission::can('admin_default_index')): ?>
        <div class="row" style="background-color: #000000;">
            <div class="col py-2 px-5">
                <?= Html::a('Добро пожаловать, ' . Yii::$app->user->identity->username, [ '/admin/default/index' ], [ 'class' => 'px-50 pull-right', 'style' => 'color:#fff;font-size:18px;text-decoration:underline;' ]) ?>
            </div>
        </div>
    <?php endif; ?>

    <header class="header-area sticky-bar">
        <div class="main-header-wrap">
            <div class="container">
                <div class="row">
                    <div class="col-xl-2 col-lg-2">
                        <div class="logo pt-40">
                            <?= Html::a(Html::img('/images/main/logo/logo.png'), [ '/main/default/index' ]) ?>
                        </div>
                    </div>
                    <div class="col-xl-7 col-lg-7 ">
                        <div class="main-menu">
                            <nav>
                                <ul>
                                    <li class="angle-shape"><a href="shop.html"> Shop <span>hot</span> </a>
                                        <ul class="mega-menu">
                                            <li><a class="menu-title" href="#">Shop Layout</a>
                                                <ul>
                                                    <li><a href="shop.html">standard style</a></li>
                                                    <li><a href="shop-grid-2-column.html">grid 2 column</a></li>
                                                    <li><a href="shop-grid-4-column.html">grid 4 column</a></li>
                                                    <li><a href="shop-grid-fullwide.html">grid full wide</a></li>
                                                    <li><a href="shop-right-sidebar.html">grid right sidebar </a></li>
                                                </ul>
                                            </li>
                                            <li><a class="menu-title" href="#">Shop Layout</a>
                                                <ul>
                                                    <li><a href="shop-list-style1.html">list style 1</a></li>
                                                    <li><a href="shop-list-style2.html">list style 2</a></li>
                                                    <li><a href="shop-list-style3.html">list style 3</a></li>
                                                    <li><a href="shop-list-fullwide.html">list full wide</a></li>
                                                    <li><a href="shop-list-sidebar.html">list with sidebar </a></li>
                                                </ul>
                                            </li>
                                            <li><a class="menu-title" href="#">Product Details</a>
                                                <ul>
                                                    <li><a href="product-details.html">tab style 1</a></li>
                                                    <li><a href="product-details-tab-2.html">tab style 2</a></li>
                                                    <li><a href="product-details-tab-3.html">tab style 3</a></li>
                                                    <li><a href="product-details-gallery.html">gallery style </a></li>
                                                    <li><a href="product-details-gallery-right.html">gallery right</a>
                                                    </li>
                                                </ul>
                                            </li>
                                            <li><a class="menu-title" href="#">Product Details</a>
                                                <ul>
                                                    <li><a href="product-details-sticky.html">sticky style</a></li>
                                                    <li><a href="product-details-sticky-right.html">sticky right</a>
                                                    </li>
                                                    <li><a href="product-details-slider-box.html">slider style</a></li>
                                                    <li><a href="product-details-affiliate.html">Affiliate style</a>
                                                    </li>
                                                </ul>
                                            </li>
                                        </ul>
                                    </li>
                                    <li><a href="shop.html">Accessories <span>hot</span> </a></li>
                                    <li><a href="contact-us.html"> Contact </a></li>
                                    <li class="angle-shape"><a href="#">Pages </a>
                                        <ul class="submenu">
                                            <li><a href="about-us.html">about us </a></li>
                                            <li><a href="cart-page.html">cart page </a></li>
                                            <li><a href="checkout.html">checkout </a></li>
                                            <li><a href="compare-page.html">compare </a></li>
                                            <li><a href="wishlist.html">wishlist </a></li>
                                            <li><a href="my-account.html">my account </a></li>
                                            <li><a href="contact-us.html">contact us </a></li>
                                            <li><a href="login-register.html">login/register </a></li>
                                        </ul>
                                    </li>
                                    <li class="angle-shape"><a href="blog.html"> Blog </a>
                                        <ul class="submenu">
                                            <li><a href="blog.html">standard style </a></li>
                                            <li><a href="blog-2-col.html">blog 2 column </a></li>
                                            <li><a href="blog-3-col.html">blog 3 column </a></li>
                                            <li><a href="blog-right-sidebar.html">blog right sidebar </a></li>
                                            <li><a href="blog-details.html">blog details </a></li>
                                            <li><a href="blog-details-right-sidebar.html">blog details right
                                                    sidebar </a></li>
                                        </ul>
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
                                <button class="icon-cart-active">
                                    <span class="icon-cart">
                                        <i class="sli sli-bag"></i>
                                        <span class="count-style">02</span>
                                    </span>
                                    <span class="cart-price">
                                        $320.00
                                    </span>
                                </button>
                                <div class="shopping-cart-content">
                                    <div class="shopping-cart-top">
                                        <h4>Shoping Cart</h4>
                                        <a class="cart-close" href="#"><i class="sli sli-close"></i></a>
                                    </div>
                                    <ul>
                                        <li class="single-shopping-cart">
                                            <div class="shopping-cart-img">
                                                <a href="#"><img alt="" src="images/main/cart/cart-1.svg"></a>
                                                <div class="item-close">
                                                    <a href="#"><i class="sli sli-close"></i></a>
                                                </div>
                                            </div>
                                            <div class="shopping-cart-title">
                                                <h4><a href="#">Product Name </a></h4>
                                                <span>1 x 90.00</span>
                                            </div>
                                        </li>
                                        <li class="single-shopping-cart">
                                            <div class="shopping-cart-img">
                                                <a href="#"><img alt="" src="images/main/cart/cart-2.svg"></a>
                                                <div class="item-close">
                                                    <a href="#"><i class="sli sli-close"></i></a>
                                                </div>
                                            </div>
                                            <div class="shopping-cart-title">
                                                <h4><a href="#">Product Name</a></h4>
                                                <span>1 x 90.00</span>
                                            </div>
                                        </li>
                                    </ul>
                                    <div class="shopping-cart-bottom">
                                        <div class="shopping-cart-total">
                                            <h4>Total : <span class="shop-total">$260.00</span></h4>
                                        </div>
                                        <div class="shopping-cart-btn btn-hover text-center">
                                            <a class="default-btn" href="checkout.html">checkout</a>
                                            <a class="default-btn" href="cart-page.html">view cart</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="setting-wrap">
                                <button class="setting-active">
                                    <i class="sli sli-settings"></i>
                                </button>
                                <div class="setting-content">
                                    <ul>
                                        <li>
                                            <h4>Currency</h4>
                                            <ul>
                                                <li><a href="#">USD</a></li>
                                                <li><a href="#">Euro</a></li>
                                                <li><a href="#">Real</a></li>
                                                <li><a href="#">BDT</a></li>
                                            </ul>
                                        </li>
                                        <li>
                                            <h4>Language</h4>
                                            <ul>
                                                <li><a href="#">English (US)</a></li>
                                                <li><a href="#">English (UK)</a></li>
                                                <li><a href="#">Spanish</a></li>
                                            </ul>
                                        </li>
                                        <li>
                                            <h4>Account</h4>
                                            <ul>
                                                <li><?= ( Yii::$app->user->isGuest ) ? Html::a('Войти', [ '/user/default/login' ]) : Html::a('Выйти', [ '/user/default/logout' ]) ?></li>
                                                <li><?= ( Yii::$app->user->isGuest ) ? Html::a('Регистрация', [ '/user/default/signup' ]) : '' ?></li>

                                                <li><a href="my-account.html">My Account</a></li>
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
                            <a href="index.html">
                                <img alt="" src="/images/main/logo/logo.png">
                            </a>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="header-right-wrap">
                            <div class="cart-wrap">
                                <button class="icon-cart-active">
                                    <span class="icon-cart">
                                        <i class="sli sli-bag"></i>
                                        <span class="count-style">02</span>
                                    </span>
                                    <span class="cart-price">
                                        $320.00
                                    </span>
                                </button>
                                <div class="shopping-cart-content">
                                    <div class="shopping-cart-top">
                                        <h4>Shoping Cart</h4>
                                        <a class="cart-close" href="#"><i class="sli sli-close"></i></a>
                                    </div>
                                    <ul>
                                        <li class="single-shopping-cart">
                                            <div class="shopping-cart-img">
                                                <a href="#"><img alt="" src="images/main/cart/cart-1.svg"></a>
                                            </div>
                                            <div class="shopping-cart-title">
                                                <h4><a href="#">Product Name </a></h4>
                                                <span>1 x 90.00</span>
                                            </div>
                                        </li>
                                        <li class="single-shopping-cart">
                                            <div class="shopping-cart-img">
                                                <a href="#"><img alt="" src="images/main/cart/cart-2.svg"></a>
                                            </div>
                                            <div class="shopping-cart-title">
                                                <h4><a href="#">Product Name</a></h4>
                                                <span>1 x 90.00</span>
                                            </div>
                                        </li>
                                    </ul>
                                    <div class="shopping-cart-bottom">
                                        <div class="shopping-cart-total">
                                            <h4>Total : <span class="shop-total">$260.00</span></h4>
                                        </div>
                                        <div class="shopping-cart-btn btn-hover text-center">
                                            <a class="default-btn" href="checkout.html">checkout</a>
                                            <a class="default-btn" href="cart-page.html">view cart</a>
                                        </div>
                                    </div>
                                </div>
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
                            <li class="menu-item-has-children "><a href="shop.html">shop</a>
                                <ul class="dropdown">
                                    <li class="menu-item-has-children"><a href="#">Shop Layout</a>
                                        <ul class="dropdown">
                                            <li><a href="shop.html">standard style</a></li>
                                            <li><a href="shop-grid-2-column.html">grid 2 column</a></li>
                                            <li><a href="shop-grid-4-column.html">grid 4 column</a></li>
                                            <li><a href="shop-grid-fullwide.html">grid full wide</a></li>
                                            <li><a href="shop-right-sidebar.html">grid right sidebar </a></li>
                                            <li><a href="shop-list-style1.html">list style 1</a></li>
                                            <li><a href="shop-list-style2.html">list style 2</a></li>
                                            <li><a href="shop-list-style3.html">list style 3</a></li>
                                            <li><a href="shop-list-fullwide.html">list full wide</a></li>
                                            <li><a href="shop-list-sidebar.html">list with sidebar </a></li>
                                        </ul>
                                    </li>
                                    <li class="menu-item-has-children"><a href="#">products details</a>
                                        <ul class="dropdown">
                                            <li><a href="product-details.html">tab style 1</a></li>
                                            <li><a href="product-details-tab-2.html">tab style 2</a></li>
                                            <li><a href="product-details-tab-3.html">tab style 3</a></li>
                                            <li><a href="product-details-gallery.html">gallery style </a></li>
                                            <li><a href="product-details-gallery-right.html">gallery right</a></li>
                                            <li><a href="product-details-sticky.html">sticky style</a></li>
                                            <li><a href="product-details-sticky-right.html">sticky right</a></li>
                                            <li><a href="product-details-slider-box.html">slider style</a></li>
                                            <li><a href="product-details-affiliate.html">Affiliate style</a></li>
                                        </ul>
                                    </li>
                                </ul>
                            </li>
                            <li><a href="shop.html">Accessories </a></li>
                            <li class="menu-item-has-children"><a href="#">pages</a>
                                <ul class="dropdown">
                                    <li><a href="about-us.html">about us </a></li>
                                    <li><a href="cart-page.html">cart page </a></li>
                                    <li><a href="checkout.html">checkout </a></li>
                                    <li><a href="compare-page.html">compare </a></li>
                                    <li><a href="wishlist.html">wishlist </a></li>
                                    <li><a href="my-account.html">my account </a></li>
                                    <li><a href="contact-us.html">contact us </a></li>
                                    <li><a href="login-register.html">login/register </a></li>
                                </ul>
                            </li>
                            <li class="menu-item-has-children "><a href="blog.html">Blog</a>
                                <ul class="dropdown">
                                    <li><a href="blog.html">standard style </a></li>
                                    <li><a href="blog-2-col.html">blog 2 column </a></li>
                                    <li><a href="blog-3-col.html">blog 3 column </a></li>
                                    <li><a href="blog-right-sidebar.html">blog right sidebar </a></li>
                                    <li><a href="blog-details.html">blog details </a></li>
                                    <li><a href="blog-details-right-sidebar.html">blog details right sidebar </a></li>
                                </ul>
                            </li>
                            <li><a href="contact-us.html">Contact us</a></li>
                        </ul>
                    </nav>
                    <!-- mobile menu navigation end -->
                </div>
                <!-- mobile menu end -->
            </div>
            <div class="mobile-curr-lang-wrap">
                <div class="single-mobile-curr-lang">
                    <a class="mobile-language-active" href="#">Language <i class="sli sli-arrow-down"></i></a>
                    <div class="lang-curr-dropdown lang-dropdown-active">
                        <ul>
                            <li><a href="#">English (US)</a></li>
                            <li><a href="#">English (UK)</a></li>
                            <li><a href="#">Spanish</a></li>
                        </ul>
                    </div>
                </div>
                <div class="single-mobile-curr-lang">
                    <a class="mobile-currency-active" href="#">Currency <i class="sli sli-arrow-down"></i></a>
                    <div class="lang-curr-dropdown curr-dropdown-active">
                        <ul>
                            <li><a href="#">USD</a></li>
                            <li><a href="#">EUR</a></li>
                            <li><a href="#">Real</a></li>
                            <li><a href="#">BDT</a></li>
                        </ul>
                    </div>
                </div>
                <div class="single-mobile-curr-lang">
                    <a class="mobile-account-active" href="#">My Account <i class="sli sli-arrow-down"></i></a>
                    <div class="lang-curr-dropdown account-dropdown-active">
                        <ul>
                            <li><?= ( Yii::$app->user->isGuest ) ? Html::a('Войти', [ '/user/default/login' ]) : Html::a('Выйти', [ '/user/default/logout' ]) ?></li>
                            <li><?= ( Yii::$app->user->isGuest ) ? Html::a('Регистрация', [ '/user/default/signup' ]) : '' ?></li>
                            <li><a href="my-account.html">My Account</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="mobile-social-wrap">
                <a class="facebook" href="#"><i class="sli sli-social-facebook"></i></a>
                <a class="twitter" href="#"><i class="sli sli-social-twitter"></i></a>
                <a class="pinterest" href="#"><i class="sli sli-social-pinterest"></i></a>
                <a class="instagram" href="#"><i class="sli sli-social-instagram"></i></a>
                <a class="google" href="#"><i class="sli sli-social-google"></i></a>
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
                                <a href="#"><img alt="" src="images/main/logo/logo.png"></a>
                                <div class="subscribe-style mt-45">
                                    <p>Subscribe to our newsleter, Enter your emil address</p>
                                    <div id="mc_embed_signup" class="subscribe-form mt-20">
                                        <form id="mc-embedded-subscribe-form" class="validate subscribe-form-style"
                                              novalidate="" target="_blank" name="mc-embedded-subscribe-form"
                                              method="post"
                                              action="http://devitems.us11.list-manage.com/subscribe/post?u=6bbb9b6f5827bd842d9640c82&amp;id=05d85f18ef">
                                            <div id="mc_embed_signup_scroll" class="mc-form">
                                                <input class="email" type="email" required=""
                                                       placeholder="Enter your email...." name="EMAIL" value="">
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
                                    <h3>Shopping</h3>
                                </div>
                                <div class="footer-list">
                                    <ul>
                                        <li><a href="shop.html">Product</a></li>
                                        <li><a href="cart-page.html">My Cart</a></li>
                                        <li><a href="wishlist.html">Wishlist</a></li>
                                        <li><a href="cart-page.html">Cart</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-4 col-6">
                            <div class="footer-widget mb-40 pl-50">
                                <div class="footer-title">
                                    <h3>Account</h3>
                                </div>
                                <div class="footer-list">
                                    <ul>
                                        <li><a href="login-register.html">Login</a></li>
                                        <li><a href="login-register.html">Register</a></li>
                                        <li><a href="contact.html">Help</a></li>
                                        <li><a href="contact-us.html">Support</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-4 col-12">
                            <div class="footer-widget mb-40">
                                <div class="footer-title">
                                    <h3>Categories</h3>
                                </div>
                                <div class="footer-list">
                                    <ul>
                                        <li><a href="shop.html">Men</a></li>
                                        <li><a href="shop.html">Women</a></li>
                                        <li><a href="shop.html">Jeins</a></li>
                                        <li><a href="shop.html">Shoes</a></li>
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
                                <p>Copyright © All Right Reserved.</p>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                            <div class="payment-mathod-2 pb-30">
                                <a href="#"><img src="images/main/icon-img/payment-2.png" alt=""></a>
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
                            <li><a href="index.html">Home </a></li>
                            <li><a href="shop.html">Shop </a></li>
                            <li><a href="shop.html">Accessories </a></li>
                            <li><a href="contact-us.html">Contact </a></li>
                            <li><a href="about-us.html">About </a></li>
                            <li><a href="blog.html">Blog </a></li>
                        </ul>
                    </nav>
                </div>
            </div>
            <div class="footer-bottom border-top-1 pt-20">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-lg-4 col-md-5 col-12">
                            <div class="footer-social pb-20">
                                <a href="#">Facebok</a>
                                <a href="#">Twitter</a>
                                <a href="#">Linkedin</a>
                                <a href="#">Instagram</a>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-12">
                            <div class="copyright text-center pb-20">
                                <p>Copyright © All Right Reserved</p>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-3 col-12">
                            <div class="payment-mathod pb-20">
                                <a href="#"><img src="assets/img/icon-img/payment.png" alt=""></a>
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
