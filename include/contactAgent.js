/**
 * Created by frank on 10/29/14.
 */

//Global variables
var map;
var markers = [];
var infoWindow;
var locationSelect;
//Acquire the address in google maps api
var search_lat;
var search_lon;

$(document).ready(function () {



    //Change Address
    var address = $("#hidden-address").text().trim();


    var url_address = address.split(' ').join('+');

    var gmaps_api_url = "https://maps.googleapis.com/maps/api/geocode/json?address=" + url_address + "?key=AIzaSyAtzwBM6mqjLD5HOk4NlGKR1R0uJ_jyF-U";


    $.ajax(
        {
            post: "GET",
            url: gmaps_api_url
        }).done(function (data) {
            //var parsedData = JSON.parse(data);


            //Deal with returned geocoords
            if (data['status'] != 'ZERO_RESULTS') {
                search_lat = data['results'][0]['geometry']['location']['lat'];
                search_lon = data['results'][0]['geometry']['location']['lng'];

                map = new google.maps.Map(document.getElementById("map-canvas"), {
                    center: new google.maps.LatLng(search_lat, search_lon),
                    zoom: 10,
                    mapTypeId: 'roadmap',
                    mapTypeControlOptions: {style: google.maps.MapTypeControlStyle.DROPDOWN_MENU}
                });
                infoWindow = new google.maps.InfoWindow();



                var myLatLng = new google.maps.LatLng(search_lat, search_lon);
                var circleOptions = {
                    center: myLatLng,
                    fillOpacity: 0,
                    strokeOpacity: 0,
                    map: map,
                    radius: 500
                }
                var myCircle = new google.maps.Circle(circleOptions);
                map.fitBounds(myCircle.getBounds());

                var latlng = new google.maps.LatLng(search_lat, search_lon);

                var marker = new google.maps.Marker({
                    map: map,
                    position: latlng,
                });

                markers.push(marker);

            }



        }).fail(function (jqXHR, textStatus, errorThrown) {
            alert(textStatus);
        });

});


/**
 Magic MySQL Query for HaverSine
 SELECT id, ( 3959 * acos( cos( radians(37) ) * cos( radians( lat ) ) * cos( radians( lng ) - radians(-122) ) + sin( radians(37) ) *
 sin( radians( lat ) ) ) ) AS distance FROM markers HAVING distance < 25 ORDER BY distance LIMIT 0 , 20;
 */

