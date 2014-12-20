/**
 * Created by frank on 12/13/14.
 */

var $messages = $('#valid-error');


$.validate({
    validateOnBlur : false, // disable validation when input looses focus
    errorMessagePosition : $messages,
    scrollToTopOnError : false // Set this property to true if you have a long form

});


$(document).ready(function () {

    var first_listing = $('#nav-top').children('.active');

    $(first_listing).trigger('click');

    var w = window.innerWidth;
    if (w > 500) {
        set_to_bottom('.navbar-fixed-top', '.sidebar-container');
        set_height_scroller('.sidebar-container');

        set_to_bottom('.navbar-fixed-top', '#main-content');
        set_height_scroller('#main-content');
    }
    else
    {
        set_to_bottom('#map-container', '#search-box');
        set_to_bottom('#search-box', '.sidebar-contaner');
        set_to_bottom('.sidebar-container', '#main-content');

    }

});

$(window).resize(function () {

    var w = window.innerWidth;
    if (w > 500) {
        set_to_bottom('.navbar-fixed-top', '.sidebar-container');
        set_height_scroller('.sidebar-container');

        set_to_bottom('.navbar-fixed-top', '#main-content');
        set_height_scroller('#main-content');
    }
    else
    {
        set_to_bottom('#map-container', '#search-box');
        set_to_bottom('#search-box', '.sidebar-contaner');
        set_to_bottom('.sidebar-container', '#main-content');

    }
});


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
