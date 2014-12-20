/**
 * Created by frank on 11/8/14.
 */

function clickListing(evt, listing_num) {

    var parent = $('#nav-top');

    //Turn off active on all listings
    $(parent).children().each(function () {
        $(this).attr('class', "");
    });


    //Update main-content
    update_main_content(listing_num);

};


function update_main_content(list_id) {
    if (list_id != undefined) {


        $.ajax(
            {
                post: "GET",
                data: {listing_id: list_id},
                url: "getListing.php",
                async: true
            }).done(function (data) {

                var parsedData = JSON.parse(data);


                var html_string = "";
                listing_images = parsedData[0]['image_ids']
                counter = 1;
                for (var i in listing_images) {
                    console.debug(listing_images);
                    image_id = listing_images[i]['image_id'];
                    caption = listing_images[i]['caption'];

                    html_string += "<div class=\"col-xs-2\">Pic " + counter + ": " + caption + "</div>";
                    html_string += "<div class=\"col-xs-2\"><img class=\"img-thumbnail navbar-thumb\" src=\"getImage.php?id=" + image_id + "\">";
                    html_string += "</div></div>";

                    counter++;
                }

                $('#image-label').html(
                    html_string
                );

                var html_string = "";


                $('#listing_id').val(parsedData[0]["listing_id"]);
                $('#address').val(parsedData[0]["street"]);
                $('#city').val(parsedData[0]["city"]);
                $('#state').val(parsedData[0]["state"]);
                $('#zip').val(parsedData[0]["zip"]);
                $('#list_price').val(parsedData[0]["list_price"]);
                $('#num_beds').val(parsedData[0]["num_beds"]);
                $('#num_baths').val(parsedData[0]["num_baths"]);
                $('#num_garages').val(parsedData[0]["num_garages"]);
                $('#sq_feet').val(parsedData[0]["sq_feet"]);
                $('#listing_desc').val(parsedData[0]["listing_desc"]);

                setDeleteButton(parsedData[0]["listing_id"]);


            }).fail(function (jqXHR, textStatus, errorThrown) {
                alert(textStatus);
            });
    }

}


function numberWithCommas(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
};


$(document).ready(function () {

    var first_listing = $('#nav-top').children('.active');

    $(first_listing).trigger('click');


});


function setDeleteButton(list_id) {
    $('#deleteListing-button').html(
        "<button type=\"submit\" class=\"btn btn-danger\" name=\"deleteListingButton\" value=" + list_id + ">Delete listing</button>"
    );
}


