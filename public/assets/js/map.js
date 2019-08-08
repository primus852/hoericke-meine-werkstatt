/* ----------------------------------------------------

    File Name: map.js
    Template Name: Linda
    Created By: HTML.Design
    http://themeforest.net/user/wpdestek

    ITS WORK ONLY CONTACT PAGE

------------------------------------------------------- */

(function($) {
    "use strict";
    var locations=[ ['<div class="infobox"><h3 class="title"><a href="#">Höricke meine Werkstatt</a></h3><span>Oderstraße 20 / 14513 Teltow</span><br /><span>03328 / 47 99 09-0</span></div>',
        52.4042844,
        13.2532239,
        2]];
    var map=new google.maps.Map(document.getElementById('map'), {
            zoom: 16, scrollwheel: false, navigationControl: true, mapTypeControl: false, scaleControl: false, draggable: true, styles: [
                {
                    "featureType": "administrative",
                    "elementType": "labels.text.fill",
                    "stylers": [
                        {
                            "color": "#444444"
                        }
                    ]
                },
                {
                    "featureType": "landscape",
                    "elementType": "all",
                    "stylers": [
                        {
                            "color": "#f2f2f2"
                        }
                    ]
                },
                {
                    "featureType": "poi",
                    "elementType": "all",
                    "stylers": [
                        {
                            "visibility": "off"
                        }
                    ]
                },
                {
                    "featureType": "road",
                    "elementType": "all",
                    "stylers": [
                        {
                            "saturation": -100
                        },
                        {
                            "lightness": 45
                        }
                    ]
                },
                {
                    "featureType": "road.highway",
                    "elementType": "all",
                    "stylers": [
                        {
                            "visibility": "simplified"
                        }
                    ]
                },
                {
                    "featureType": "road.arterial",
                    "elementType": "labels.icon",
                    "stylers": [
                        {
                            "visibility": "off"
                        }
                    ]
                },
                {
                    "featureType": "transit",
                    "elementType": "all",
                    "stylers": [
                        {
                            "visibility": "off"
                        }
                    ]
                },
                {
                    "featureType": "water",
                    "elementType": "all",
                    "stylers": [
                        {
                            "color": "#5D9ED3"
                        },
                        {
                            "visibility": "on"
                        }
                    ]
                }
            ], center: new google.maps.LatLng(52.4042844, 13.2532239), mapTypeId: google.maps.MapTypeId.ROADMAP
        }

    );
    var infowindow=new google.maps.InfoWindow();
    var marker,
        i;
    for (i=0;
         i < locations.length;
         i++) {
        marker=new google.maps.Marker( {
                position: new google.maps.LatLng(locations[i][1], locations[i][2]), map: map, icon: $('#js-map-icon').attr('data-url')
            }
        );
        google.maps.event.addListener(marker, 'click', (function(marker, i) {
                return function() {
                    infowindow.setContent(locations[i][0]);
                    infowindow.open(map, marker);
                }
            }
        )(marker, i));
    }
})(jQuery);