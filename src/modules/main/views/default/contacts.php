<?php

$this->title = 'Контакты';

$this->params['breadcrumbs'][] = $this->title;

?>

<div class="contact-area pt-100 pb-100">
    <div class="container">
        <div class="row">
            <div class="col-lg-5 col-md-6">
                <div class="contact-info-area">
                    <h2> Наши контакты </h2>
                    <div class="contact-info-wrap">
                        <div class="single-contact-info">
                            <div class="contact-info-icon">
                                <i class="sli sli-location-pin"></i>
                            </div>
                            <div class="contact-info-content">
                                <p>Украина, г. Киев</p>
                            </div>
                        </div>
                        <div class="single-contact-info">
                            <div class="contact-info-icon">
                                <i class="sli sli-envelope"></i>
                            </div>
                            <div class="contact-info-content">
                                <p><a href="mailto:storeaquista@gmail.com">storeaquista@gmail.com</a></p>
                            </div>
                        </div>
                        <div class="single-contact-info">
                            <div class="contact-info-icon">
                                <i class="sli sli-screen-smartphone"></i>
                            </div>
                            <div class="contact-info-content">
                                <p><a href="tel:+380676577171"> +38 (067) 657-71-71 </a></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-7 col-md-6">
                <div class="contact-from contact-shadow">
                    <form id="contact-form" action="assets/mail.php" method="post">
                        <input name="name" type="text" placeholder="Имя">
                        <input name="email" type="email" placeholder="Почтовый ящик">
                        <input name="subject" type="text" placeholder="Тема">
                        <textarea name="message" placeholder="Ваше сообщение"></textarea>
                        <button class="submit" type="submit">Отправить</button>
                    </form>
                    <p class="form-messege"></p>
                </div>
            </div>
        </div>
        <!--        <div class="contact-map pt-100">-->
        <!--            <div id="map"></div>-->
        <!--        </div>-->
    </div>
</div>

<!--<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCGM-62ap9R-huo50hJDn05j3x-mU9151Y"></script>-->
<!--<script>-->
<!--    function init() {-->
<!--        var mapOptions = {-->
<!--            zoom: 12,-->
<!--            scrollwheel: false,-->
<!--            center: new google.maps.LatLng(50.4466085, 30.5165542),-->
<!--            styles:-->
<!--                [-->
<!--                    {-->
<!--                        "featureType": "landscape",-->
<!--                        "stylers": [-->
<!--                            {-->
<!--                                "hue": "#FFBB00"-->
<!--                            },-->
<!--                            {-->
<!--                                "saturation": 43.400000000000006-->
<!--                            },-->
<!--                            {-->
<!--                                "lightness": 37.599999999999994-->
<!--                            },-->
<!--                            {-->
<!--                                "gamma": 1-->
<!--                            }-->
<!--                        ]-->
<!--                    },-->
<!--                    {-->
<!--                        "featureType": "road.highway",-->
<!--                        "stylers": [-->
<!--                            {-->
<!--                                "hue": "#FFC200"-->
<!--                            },-->
<!--                            {-->
<!--                                "saturation": -61.8-->
<!--                            },-->
<!--                            {-->
<!--                                "lightness": 45.599999999999994-->
<!--                            },-->
<!--                            {-->
<!--                                "gamma": 1-->
<!--                            }-->
<!--                        ]-->
<!--                    },-->
<!--                    {-->
<!--                        "featureType": "road.arterial",-->
<!--                        "stylers": [-->
<!--                            {-->
<!--                                "hue": "#FF0300"-->
<!--                            },-->
<!--                            {-->
<!--                                "saturation": -100-->
<!--                            },-->
<!--                            {-->
<!--                                "lightness": 51.19999999999999-->
<!--                            },-->
<!--                            {-->
<!--                                "gamma": 1-->
<!--                            }-->
<!--                        ]-->
<!--                    },-->
<!--                    {-->
<!--                        "featureType": "road.local",-->
<!--                        "stylers": [-->
<!--                            {-->
<!--                                "hue": "#FF0300"-->
<!--                            },-->
<!--                            {-->
<!--                                "saturation": -100-->
<!--                            },-->
<!--                            {-->
<!--                                "lightness": 52-->
<!--                            },-->
<!--                            {-->
<!--                                "gamma": 1-->
<!--                            }-->
<!--                        ]-->
<!--                    },-->
<!--                    {-->
<!--                        "featureType": "water",-->
<!--                        "stylers": [-->
<!--                            {-->
<!--                                "hue": "#0078FF"-->
<!--                            },-->
<!--                            {-->
<!--                                "saturation": -13.200000000000003-->
<!--                            },-->
<!--                            {-->
<!--                                "lightness": 2.4000000000000057-->
<!--                            },-->
<!--                            {-->
<!--                                "gamma": 1-->
<!--                            }-->
<!--                        ]-->
<!--                    },-->
<!--                    {-->
<!--                        "featureType": "poi",-->
<!--                        "stylers": [-->
<!--                            {-->
<!--                                "hue": "#00FF6A"-->
<!--                            },-->
<!--                            {-->
<!--                                "saturation": -1.0989010989011234-->
<!--                            },-->
<!--                            {-->
<!--                                "lightness": 11.200000000000017-->
<!--                            },-->
<!--                            {-->
<!--                                "gamma": 1-->
<!--                            }-->
<!--                        ]-->
<!--                    }-->
<!--                ]-->
<!--        };-->
<!--        var mapElement = document.getElementById('map');-->
<!--        var map = new google.maps.Map(mapElement, mapOptions);-->
<!--        // var marker = new google.maps.Marker({-->
<!--        //     position: new google.maps.LatLng(50.4306698, 30.5024901),-->
<!--        //     map: map,-->
<!--        //     icon: '/images/main/icon-img/2.png',-->
<!--        //     animation: google.maps.Animation.BOUNCE,-->
<!--        //     title: 'Snazzy!'-->
<!--        // });-->
<!--    }-->
<!---->
<!--    google.maps.event.addDomListener(window, 'load', init);-->
<!--</script>-->
