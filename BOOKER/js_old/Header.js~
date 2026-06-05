var path = window.location.pathname;
path = decodeURIComponent(path);

$(document).ready(function () {
    $("#header .menu .menu-item,.homepage-links a").each(function () {
        var href = $(this).attr("href");

        if (href&&path&&path.trim() == href.trim().split(/[?#]/)[0]) {
            $(this).addClass("active");
        }
    })
    if ($("#login-form").length) {
        $("#login-form").validate({
            rules: {
                Email: {
                    required: true,
                    email: true
                },
                Password: "required",
            }
        });
    }
    $(".favorite-link").on("click",function (event) {
        if( $(".favorite-link").data('favorites')==0){
            event.preventDefault();
            openPopup('Ubooker','You have no favorite models on your list - to add models, just click the heart icon at the top of their profile picture');
        }

    });

    if (!/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
        $(".support-link").qtip({
            position: {
                my: 'top right',
                at: 'bottom center',
                target: false, // Defaults to target element
                container: false, // Defaults to $(document.body)
                viewport: false, // Requires Viewport plugin
                adjust: {
                    x: 7, y: 5, // Minor x/y adjustments
                    mouse: true, // Follow mouse when using target:'mouse'
                    resize: true, // Reposition on resize by default
                    method: 'flip flip' // Requires Viewport plugin
                },
                effect: function (api, pos, viewport) {
                    $(this).animate(pos, {
                        duration: 200,
                        queue: false
                    });
                }
            },
            show: {
                target: false, // Defaults to target element
                event: 'mouseenter', // Show on mouse over by default
                effect: true, // Use default 90ms fade effect
                delay: 90, // 90ms delay before showing
                solo: false, // Do not hide others when showing
                ready: false, // Do not show immediately
                modal: { // Requires Modal plugin
                    on: false, // No modal backdrop by default
                    effect: true, // Mimic show effect on backdrop
                    blur: true, // Hide tooltip by clicking backdrop
                    escape: true // Hide tooltip when Esc pressed
                }
            },
            hide: {
                target: false, // Defaults to target element
                event: 'mouseleave', // Hide on mouse out by default
                effect: true, // Use default 90ms fade effect
                delay: 0, // No hide delay by default
                fixed: false, // Non-hoverable by default
                inactive: false, // Do not hide when inactive
                leave: 'window', // Hide when we leave the window
                distance: false // Don't hide after a set distance
            },
            style: {
                classes: 'qtip-youtube'
            }
        });
    }
});
function showApprovalPopup() {

        $.magnificPopup.open({
            items: {
                src: '<div class="custom_popup">\n    <h3 class="popup_title">Ubooker</h3>\n    <p class="description">\n        THE COMPLETE FUNCTIONALITIES OF THE SITE WILL BE ACTIVE AFTER THE VALIDATION OF YOUR ACCOUNT\n    </p>\n    <button title="Close (Esc)" type="button" class="mfp-close">×</button>\n</div>',
                type: 'inline'
            }
        });

}
