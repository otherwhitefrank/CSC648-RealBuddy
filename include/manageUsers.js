function clickUser(evt, user_num) {

    var target = evt.target;

    target = $(target).closest("li");

    var parent = $('#nav-top');


    //Turn off active on all users
    $(parent).children().each(function () {
        $(this).attr('class', "");
    });

    //Set active to selection
    target = $(target).attr('class', 'active');

    //Update main-content
    update_main_content(user_num);

};


function update_main_content(user_id) {
    if (user_id != undefined) {

        $.ajax(
            {
                post: "GET",
                data: {user_id: user_id},
                url: "getUser.php",
                async: true
            }).done(function (data) {

                console.debug(data);
                var user = JSON.parse(data);

                console.debug(user);
                counter = 0;
                $('#main-input-name').html(
                    user['first_name'] + ", " + user['last_name']
                );

                $('#main-input-street').html(
                    user['street']
                );

                //Update search address in search bar
                $('#main-input-address').html(
                    user["city"] + ", " +
                    user["state"] + ", " +
                    user["zip"]
                );

                userRole = user["role"];
                userid = user["id"];

                var htmlStr = "<select multiple class=\"form-control\" name=\"userRoleSelection\">";
                htmlStr += "<option value=\"" + userid + "_user\" " + (userRole == "user" ? "selected" : "") + ">Registered User</option>";
                htmlStr += "<option value=\"" + userid + "_agent\" " + (userRole == "agent" ? "selected" : "") + ">Realtor</option>";
                htmlStr += "<option value=\"" + userid + "_admin\" " + (userRole == "admin" ? "selected" : "") + ">Administrator</option>";
                htmlStr += " </select>";
                console.debug(htmlStr);

                $('#main-updateRole-options').html(
                    htmlStr
                );

                $('#main-deleteUser-button').html(
                    "<button type=\"submit\" class=\"btn btn-danger\" name=\"deleteUserButton\" value=\"" + user['id'] + "\">Delete User</button>"
                );
            }).fail(function (jqXHR, textStatus, errorThrown) {
                console.log(textStatus);
            });
    }
};

function numberWithCommas(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
};

$(document).ready(function () {

    var first_user = $('#nav-top').children('.active');

    $(first_user).trigger('click');

});


$(document).ready(function () {

    var first_listing = $('#nav-top').children('.active');

    $(first_listing).trigger('click');

    var w = window.innerWidth;

    set_to_bottom('.navbar-fixed-top', '.sidebar-container');
    set_height_scroller('.sidebar-container');

    set_to_bottom('.navbar-fixed-top', '#main-content');
    set_height_scroller('#main-content');


});

$(window).resize(function () {

    var w = window.innerWidth;
    set_to_bottom('.navbar-fixed-top', '.sidebar-container');
    set_height_scroller('.sidebar-container');

    set_to_bottom('.navbar-fixed-top', '#main-content');
    set_height_scroller('#main-content');
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

    $(selector).css({position: 'absolute', top: boxBottom});

}
