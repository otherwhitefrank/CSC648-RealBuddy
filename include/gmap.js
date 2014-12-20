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

    //Populate forms with previous values.

    //Change Address
    var address = $("#hidden-address").text().trim();
    $("#input-address").val(address);
    //Change Min price
    var min_price = $("#hidden-min-price").text().trim();
    $("#input-min-price").val(min_price);
    //Change Max price
    var max_price = $("#hidden-max-price").text().trim();
    $("#input-max-price").val(max_price);
    //Change distance
    var search_distance = $("#hidden-distance").text().trim();
    $("#input-distance").val(search_distance);
    //Change num bedrooms
    var num_bedrooms = $("#hidden-num-bedrooms").text().trim();
    $("#input-num-bedrooms").val(num_bedrooms);
    //Change num bathrooms
    var num_bathrooms = $("#hidden-num-bathrooms").text().trim();
    $("#input-num-bathrooms").val(num_bathrooms);
    //Change num garages
    var num_garages = $("#hidden-num-garages").text().trim();
    $("#input-num-garages").val(num_garages);

    var url_address = address.split(' ').join('+');

    var gmaps_api_url = "https://maps.googleapis.com/maps/api/geocode/json?address=" + url_address + "?key=AIzaSyB-ssGXLniMzVEATITsDPKXIQXZ0ggEwqE";


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

                window.RealBuddyMap = map;

                infoWindow = new google.maps.InfoWindow();

                var dist_calc = 470 * search_distance;

                var myLatLng = new google.maps.LatLng(search_lat, search_lon);
                var circleOptions = {
                    center: myLatLng,
                    fillOpacity: 0,
                    strokeOpacity: 0,
                    map: map,
                    radius: dist_calc
                }
                var myCircle = new google.maps.Circle(circleOptions);
                map.fitBounds(myCircle.getBounds());


                $.ajax(
                    {
                        post: "GET",
                        data: {
                            address: address,
                            min_price: min_price,
                            max_price: max_price,
                            search_distance: search_distance,
                            num_bedrooms: num_bedrooms,
                            num_bathrooms: num_bathrooms,
                            num_garages: num_garages,
                            search_lat: search_lat,
                            search_lon: search_lon
                        },
                        url: "getGeoCoords.php",
                        async: false
                    }).done(function (data) {
                        var parsedData = JSON.parse(data);

                        //alert(parsedData);
                        var i = 0;

                        for (i = 0; i < parsedData.length; i++) {

                            var html = "<b>" + parsedData[i].listing_id + "</b>";
                            var new_lat = parsedData[i].lat;
                            var new_lon = parsedData[i].lon;
                            var latlng = new google.maps.LatLng(new_lat, new_lon);


                            var marker = new google.maps.Marker({
                                map: map,
                                position: latlng,
                                icon: 'http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=' + (i+ 1) + '|FE6256|000000',
                                listing_id: parsedData[i].listing_id
                            });
                            google.maps.event.addListener(marker, 'click', function () {
                                clickListingFromMap(this.listing_id);
                                map.panTo(this.getPosition());
                            });
                            markers.push(marker);
                        }
                    }).fail(function (jqXHR, textStatus, errorThrown) {
                        alert(textStatus);
                    });

            }
            else
            {
                //Setup basic map, centered on 94109 with no markers.

                var search_lat = 37.788015;
                var search_lon = -122.416413;

                map = new google.maps.Map(document.getElementById("map-canvas"), {
                    center: new google.maps.LatLng(search_lat, search_lon),
                    zoom: 10,
                    mapTypeId: 'roadmap',
                    mapTypeControlOptions: {style: google.maps.MapTypeControlStyle.DROPDOWN_MENU}
                });
                infoWindow = new google.maps.InfoWindow();


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

