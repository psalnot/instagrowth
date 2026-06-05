/**
 * Created by username on 7/7/2016.
 */
//$("#privacy,#terms").on("click", function () {
//    $("#popup").html($(this).find("span").html());
//    $.magnificPopup.open({
//        items: {
//            src: "#popup",
//            type: 'inline'
//        },
//        modal: false
//
//
//    });
//});


//------------mailchimp-------------
$("#mc-embedded-subscribe-form .checkbox input").on('ifClicked', function (event) {
    $(".the-hide-wrap").slideToggle();

});
function validateEmail($email) {
    var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
    return emailReg.test($email);
}

$(document).ready(function () {
    var $form = $('#mc-embedded-subscribe-form');

    if ($form.length > 0) {
        $form.validate({
            rules: {
                FNAME: "required",
                LNAME: "required",
                EMAIL: "required"
            },
            submitHandler: function (form) {
                if ($form.valid()) {
                    $.ajax({
                        type: $form.attr('method'),
                        url: $form.attr('action'),
                        data: $form.serialize(),
                        cache: false,
                        dataType: 'json',
                        contentType: "application/json; charset=utf-8",
                        error: function (err) {
                            alert("Could not connect to the registration server. Please try again later.");
                        },
                        success: function (data) {

                            if (data.result != "success") {
                                $.magnificPopup.open({
                                    items: {
                                        src: ".popup-not-so-ok",
                                        type: 'inline'
                                    },
                                    modal: false


                                });
                            } else {
                                $.magnificPopup.open({
                                    items: {
                                        src: ".popup-ok",
                                        type: 'inline'
                                    },
                                    modal: false


                                });
                            }
                        }
                    });
                }

            }
        });
    }


});

//------------/mailchimp-------------


$(document).ready(function () {


    $("a.grid-btn").click(function (e) {
        e.preventDefault();
        $(this).toggleClass("double-grid");
        if ($(".model-item").hasClass("double-item")) {
            $(".model-item").removeClass("double-item");
        } else {
            $(".model-item").addClass("double-item");
        }

    });

    /* iCheck - disabled */
    /*$('input').iCheck({
        checkboxClass: 'icheckbox_square',
        radioClass: 'iradio_minimal',
        increaseArea: '20%' // optional
    });*/
});

var loadingnavitem = [{
    elements: $(".menu-item"),
    properties: 'transition.slideLeftBigIn',
    options: {

        stagger: 150,
        queue: false,
        easing: "linear"

    }
}];
function menu_items_animate() {
    $.Velocity.RunSequence(loadingnavitem);

}


function openNav() {
    menu_items_animate();
    document.getElementById("mySidenav").style.width = "320px";
    document.getElementById("menu-mask").style.display = "block";
}

function closeNav() {
    document.getElementById("mySidenav").style.width = "0";
    document.getElementById("menu-mask").style.display = "none";
}
$("#menu-mask").click(function () {
    document.getElementById("mySidenav").style.width = "0";
    document.getElementById("menu-mask").style.display = "none";
});
