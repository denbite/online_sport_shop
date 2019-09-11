<?php


$this->title = 'Размеры';

$this->params['breadcrumbs'][] = $this->title;


?>

<div class="pt-100 pb-100">
    <div class="container">
        <div class="row">
            <div class="col-12 pb-50">
                <h2 style="font-weight: 600;margin: 0 0 15px;font-size: 35px;line-height: 30px;">
                    Размеры
                </h2>
            </div>
            <div id="arena" class="col-12 pb-80">
                <div class="contact-from contact-shadow ml-0">
                    <?= \yii\helpers\Html::img('/images/sizes-arena-men.jpg',
                                               [
                                                   'class' => 'col-12 pb-20',
                                                   'alt' => 'arena-men',
                                               ]) ?>
                </div>
            </div>
            <div class="col-12 pb-80">
                <div class="contact-from contact-shadow ml-0">
                    <?= \yii\helpers\Html::img('/images/sizes-arena-women.jpg',
                                               [
                                                   'class' => 'col-12 pb-20',
                                                   'alt' => 'arena-men',
                                               ]) ?>
                </div>
            </div>
            <div id="funkita" class="col-12 pb-80">
                <div class="contact-from contact-shadow ml-0">
                    <?= \yii\helpers\Html::img('/images/sizes-funkita-women.jpg',
                                               [
                                                   'class' => 'col-12 pb-20',
                                                   'alt' => 'arena-men',
                                               ]) ?>
                </div>
            </div>
            <div id="funky trunks" class="col-12 pb-80">
                <div class="contact-from contact-shadow ml-0">
                    <?= \yii\helpers\Html::img('/images/sizes-funky-trunks-men.png',
                                               [
                                                   'class' => 'col-12 pb-20',
                                                   'alt' => 'arena-men',
                                               ]) ?>
                </div>
            </div>
            <div id="saucony" class="col-12">
                <div class="contact-from contact-shadow ml-0">
                    <?= \yii\helpers\Html::img('/images/sizes-saucony.jpg',
                                               [
                                                   'class' => 'col-12 pb-20',
                                                   'alt' => 'arena-men',
                                               ]) ?>
                </div>
            </div>
        </div>
    </div>

</div>
