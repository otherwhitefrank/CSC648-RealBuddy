/**
 * Created by frank on 11/8/14.
 */

function clickListing(evt, listing_num) {

    var target = evt.target;

    target = $(target).closest("li");

    var parent = $('#nav-top');


    //Turn off active on all listings
    $(parent).children().each(function () {
        $(this).attr('class', "");
    });

    //Set active to selection

    target = $(target).attr('class', 'active');


    //Update main-content
    update_main_content(listing_num);


};


function clickListingFromMap(listing_id) {

    var encoded_id = "#navbarListing" + listing_id;

    var parent_li = $(encoded_id);
    var parent = $('#nav-top');

    //Turn off active on all listings
    $(parent).children().each(function () {
        $(this).attr('class', "");
    });

    $(parent_li).attr('class', 'active');


    $('#nav-top').scrollTop($('#nav-top').scrollTop() + $(parent_li).position().top);


    //Update main-content
    update_main_content(listing_id);


};

function focus_map(list_id) {

    if (list_id != undefined) {
        $.ajax(
            {
                post: "GET",
                data: {listing_id: list_id},
                url: "getGeoCoordsFromListId.php",
                async: true
            }).done(function (data) {

                var parsedData = JSON.parse(data);

                var lat = parsedData[0]['lat'];
                var lon = parsedData[0]['lon'];

                var dist_calc = 470 * 20;

                var myLatLng = new google.maps.LatLng(lat, lon);
                var circleOptions = {
                    center: myLatLng,
                    fillOpacity: 0,
                    strokeOpacity: 0,
                    map: map,
                    radius: dist_calc
                }
                var myCircle = new google.maps.Circle(circleOptions);

                //Make sure google maps has been initialized, and made available globally
                if (window.RealBuddyMap != undefined) {
                    window.RealBuddyMap.fitBounds(myCircle.getBounds());
                }


            }).fail(function (jqXHR, textStatus, errorThrown) {
                console.log(textStatus);
            });
    }

};

function update_main_content(list_id) {
    if (list_id != undefined) {
        focus_map(list_id);

        $.ajax(
            {
                post: "GET",
                data: {listing_id: list_id},
                url: "getListing.php",
                async: true
            }).done(function (data) {

                var parsedData = JSON.parse(data);

                counter = 0;
                var html_string = "";
                listing_images = parsedData[0]['image_ids']
                for (var i in listing_images) {
                    console.debug(listing_images);
                    image_id = listing_images[i]['image_id'];
                    caption = listing_images[i]['caption'];
                    console.debug(image_id);
                    console.debug(caption);

                    if (counter == 0) {
                        html_string += "<div class=\"active item\" data-slide-number=\"" + counter + "\">";
                    }
                    else {
                        html_string += "<div class=\"item\" data-slide-number=\"" + counter + "\">";
                    }

                    html_string += "<img src=\"getImage.php?id=" + image_id + "\"></div>";
                    counter++;

                }

                $('#carousel-inner').html(
                    html_string
                );

                counter = 0;
                var html_string = "";
                for (var i in listing_images) {
                    image_id = listing_images[i]['image_id'];
                    caption = listing_images[i]['caption'];


                    html_string += "<div id=\"slide-content-" + counter + "\">";
                    html_string += "<h2>" + caption + "</h2>";
                    html_string += "</div>";

                    counter++;
                }

                $('#slide-content').html(
                    html_string
                );


                counter = 0;
                var html_string = "";
                for (var i in listing_images) {
                    image_id = listing_images[i]['image_id'];
                    caption = listing_images[i]['caption'];

                    html_string += "<li class=\"thumbnail-container col-lg-1\">";
                    html_string += "<a class=\"thumbnail\" id=\"carousel-selector-" + counter + "\"><img src=\"getImage.php?id=" + image_id + "\"></a>";
                    html_string += "</li>";

                    counter++;
                }

                $('#hide-bullets').html(
                    html_string
                );


                $.ajax(
                    {
                        post: "GET",
                        data: {listing_id: list_id},
                        url: "getListing.php",
                        async: true
                    }).done(function (data) {

                        var parsedData = JSON.parse(data);


                        var html_string = "";


                        var html_string2 = "";
                        html_string2 += "<div id=\"main-content-text\" class=\"col-lg-12\">";
                        var curr_user_id = $('#hidden-user-id').html();
                        curr_user_id = curr_user_id.trim();

                        if (curr_user_id != null) {
                            if (curr_user_id != "-1") {
                                html_string2 += "<a href=\"contactAgent.php?id=" + list_id
                                + "&address=" + parsedData[0]["street"]
                                + "&city=" + parsedData[0]["city"]
                                + "&state=" + parsedData[0]["state"]
                                + "&zip=" + parsedData[0]["zip"]
                                + "\" class=\"contactAgent\">Contact Agent!</a>";
                            }
                            else {
                                html_string2 += "<a " + "onClick=\"promptContactLogin();\" class=\"contactAgent\">Contact Agent!</a>";
                            }
                        }

                        html_string2 += "<h3>Sale Price: " + "$" + numberWithCommas(parsedData[0]["list_price"]) + "</h3>";
                        html_string2 += "<h4>Sq. Feet: " + parsedData[0]["sq_feet"] + "</h4>";
                        html_string2 += "<h4>Num. Beds: " + parsedData[0]["num_beds"] + "</h4>";
                        html_string2 += "<h4>Num. Baths: " + parsedData[0]["num_baths"] + "</h4>";
                        html_string2 += "<h4>Num. Car Garage: " + parsedData[0]["num_garages"] + "</h4>";
                        html_string2 += "<h3>" + parsedData[0]["street"];
                        html_string2 += "<small> - " + parsedData[0]["city"] + ", " + parsedData[0]['state'] + ", " + parsedData[0]['zip'] + "</small>" + "</h3>";
                        html_string2 += "</a>";
                        html_string2 += "<h3>" + parsedData[0]['listing_desc'] + "</h3>";
                        html_string2 += "</div>";
                        html_string2 += "</div>";


                        $('#main-meta-data').html(
                            html_string
                        );

                        $('#main-meta-data2').html(
                            html_string2
                        );

                        //Update search address in search bar
                        $('#input-address').val(
                            parsedData[0]["street"] + ", " +
                            parsedData[0]["city"] + ", " +
                            parsedData[0]["state"] + ", " +
                            parsedData[0]["zip"]
                        );

                        //Restart the carousel
                        startCarousel();

                        // approveListing.php related stuff
                        setDeleteButton(list_id);
                        setApproveButton(list_id);
                    }).fail(function (jqXHR, textStatus, errorThrown) {
                        console.log(textStatus);
                    });

            }).fail(function (jqXHR, textStatus, errorThrown) {
                console.log(textStatus);
            });
    }
};

function numberWithCommas(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
};

function setDeleteButton(list_id) {
    $('#deleteListing-button').html(
        "<button type=\"submit\" class=\"btn btn-danger\" name=\"deleteListingButton\" value=" + list_id + ">Delete listing</button>"
    );
}

function setApproveButton(list_id) {
    $('#approveListing-button').html(
        "<button type=\"submit\" class=\"btn btn-success\" name=\"approveListingButton\" value=" + list_id + ">Approve listing</button>"
    );
}
$(document).ready(function () {

    var first_listing = $('#nav-top').children('.active');

    $(first_listing).trigger('click');

    startCarousel();

    var w = window.innerWidth;
    if (w > 500) {
        set_to_bottom('#map-container', '.sidebar-container');
        set_height_scroller('.sidebar-container');

        set_to_bottom('#search-box', '#main-content');
        set_height_scroller('#main-content');
    }
    else
    {
        set_to_bottom('#map-container', '#search-box');
        set_to_bottom('#search-box', '.sidebar-container');
        set_to_bottom('.sidebar-container', '#main-content');

    }

});

$(window).resize(function () {

    var w = window.innerWidth;
    if (w > 500) {
        set_to_bottom('#map-container', '.sidebar-container');
        set_height_scroller('.sidebar-container');
        set_to_bottom('#search-box', '#main-content');
        set_height_scroller('#main-content');

    }
    else
    {
        set_to_bottom('#map-container', '#search-box');
        set_to_bottom('#search-box', '.sidebar-container');
        set_to_bottom('.sidebar-container', '#main-content');

    }
});

function startCarousel() {
    $('#myCarousel').carousel({
        interval: 5000
    });

    $('#carousel-text').html($('#slide-content-0').html());

    //Handles the carousel thumbnails
    $('[id^=carousel-selector-]').click(function () {
        var id_selector = $(this).attr("id");
        var id = id_selector.substr(id_selector.length - 1);
        var id = parseInt(id);
        $('#myCarousel').carousel(id);
    });


    // When the carousel slides, auto update the text
    $('#myCarousel').on('slid.bs.carousel', function (e) {
        var id = $('.item.active').data('slide-number');
        $('#carousel-text').html($('#slide-content-' + id).html());
    });

};

function set_height_scroller(selector) {
    var w = window.innerWidth;
    var h = window.innerHeight;

    var box = $(selector);
    var boxPosition = box.position();
    var boxTop = boxPosition.top;
    var boxHeight = box.height();
    var boxNewHeight = h - (+boxTop);
    box.css('height', boxNewHeight + 'px');
}


function set_to_bottom(source, selector) {

    var MARGIN = 10;

    var source = $(source);
    var sourceHeight = source.height();
    var sourcePosition = source.position();
    var boxBottom = sourcePosition.top + sourceHeight + MARGIN;

    $(selector).css({position:'absolute', top: boxBottom});

}



