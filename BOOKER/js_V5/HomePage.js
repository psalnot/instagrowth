var controller = new ScrollMagic.Controller();
$(document).ready(function () {
    $('.slider').slick({
        dots: false,
        arrows: false,
        infinite: true,
        speed: 300,
        slidesToShow: 6,
        slidesToScroll: 1,
        autoplay: true,
        autoplaySpeed: 3000,
        responsive: [
            {
                breakpoint: 1025,
                settings: {
                    slidesToShow: 4,
                    slidesToScroll: 1,
                    infinite: true,
                }
            },
            {
                breakpoint: 769,
                settings: {
                    slidesToShow: 3,
                    slidesToScroll: 1,
                    infinite: true,
                }
            },
            {
                breakpoint: 370,
                settings: {
                    slidesToShow: 3,
                    slidesToScroll: 1,
                }
            },
            {
                breakpoint: 300,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 1
                }
            }
            // You can unslick at a given breakpoint now by adding:
            // settings: "unslick"
            // instead of a settings object
        ],
    });
    $('#client-slider,#model-slider,#client-slider-mobile,#model-slider-mobile').slick({
        dots: false,
        arrows: false,
        infinite: true,
        speed: 300,
        slidesToShow: 1,
        slidesToScroll: 1,
        autoplay: 0,
        autoplaySpeed: 3000
    });
    //testimonial-slider
    $('#testimonial-slider').slick({
        adaptiveHeight: true,
        dots: true,
        arrows: false,
        infinite: true,
        speed: 300,
        slidesToShow: 1,
        slidesToScroll: 1,
        autoplay: true,
        autoplaySpeed: 10000,
        responsive: [

            {
                breakpoint: 768,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1
                }
            }
            // You can unslick at a given breakpoint now by adding:
            // settings: "unslick"
            // instead of a settings object
        ],
    });
    $(".animated:in-viewport").each(function () {
        $(this).addClass($(this).data("animated"));
    });
    $(window).on("scroll", function () {
        $(".animated:in-viewport").each(function () {
            $(this).addClass($(this).data("animated"));
        });

    })
    $.instagramFeed({
        'username': 'ubookermodels',
        'container': "#instagram-feed",
        'display_profile': true,
        'display_biography': false,
        'display_gallery': true,
        'callback': null,
        'styling': false,
        'items': 6,
        'items_per_row': 3,
        'margin': 1,
        'lazy_load': true,
        'on_error': console.error
    });

});


function scrolToNext(elem) {
    jQuery("body,html").animate({
        scrollTop: jQuery(elem).parent().next().offset().top
    }, 800)
}

function pt(t, e) {
    for (var n = 0; n < e.length; n++) {
        var r = e[n];
        r.enumerable = r.enumerable || !1,
            r.configurable = !0,
        "value" in r && (r.writable = !0),
            Object.defineProperty(t, r.key, r)
    }
}

var dt = function () {
    function t(e) {
        !function (t, e) {
            if (!(t instanceof e))
                throw new TypeError("Cannot call a class as a function")
        }(this, t),
            this.el = e,
            this.box = e.querySelector(".js-contact-box"),
            this.xCursor = 0,
            this.yCursor = 0,
            this.xCurrent = 50,
            this.yCurrent = 50,
            this.ease = 0.0001,
            this.isVisible = !1,
            this.bindAll(),
            this.init()
    }

    var e, n, r;
    return e = t,
    (n = [{
        key: "render",
        value: function () {
            this.rAF && this.isVisible && (this.yCurrent += (this.yCursor - this.yCurrent) * this.ease,
                this.xCurrent += (this.xCursor - this.xCurrent) * this.ease,
                this.xCurrent<20?this.xCurrent=20:this.xCurrent,
                this.xCurrent>65?this.xCurrent=65:this.xCurrent,
                this.box.style.transform = "translate3d(-".concat(this.xCurrent.toFixed(2), "%, -").concat(this.yCurrent.toFixed(2), "%, 0)"),
                requestAnimationFrame(this.render))
        }
    }, {
        key: "observer",
        value: function () {
            var t = this;
            new IntersectionObserver((function (e) {
                    e.forEach((function (e) {
                            return t.isVisible = e.intersectionRatio > 0
                        }
                    ))
                }
            )).observe(this.el)
        }
    }, {
        key: "bindAll",
        value: function () {
            var t = this;
            ["render", "observer"].forEach((function (e) {
                    t[e] = t[e].bind(t)
                }
            ))
        }
    }, {
        key: "init",
        value: function () {
            var t = this;
            this.observer(),
                window.addEventListener("mousemove", (function (e) {
                        t.event = e,
                            t.rAF = !0,
                            t.render(),
                            t.xCursor = t.event.clientX / window.innerWidth * 60 + 20,
                            t.yCursor = t.event.clientY / window.innerHeight * 60 + 20
                    }
                )),
                this.el.addEventListener("mouseenter", (function (e) {
                        t.box.style.willChange = "transform"
                    }
                )),
                this.el.addEventListener("mouseleave", (function (e) {
                        t.box.style.willChange = "none"
                    }
                ))
        }
    }]) && pt(e.prototype, n),
    r && pt(e, r),
        t
}();

function vt(t, e) {
    for (var n = 0; n < e.length; n++) {
        var r = e[n];
        r.enumerable = r.enumerable || !1,
            r.configurable = !0,
        "value" in r && (r.writable = !0),
            Object.defineProperty(t, r.key, r)
    }
}

var card_1;
var card_2;
var footer;

var needs_build = true;
$(window).load(function () {
    if ($(window).width() >= 768) {
        buildSecenes();

    }
})


function buildSecenes() {
    if (needs_build) {
        needs_build = false;
        card_1 = new ScrollMagic.Scene({
                triggerElement: "#card-1 .spacer",
                duration: $("#card-1").height()+290
            }
        )
            .setPin("#card-1 .card-img", {pushFollowers: false})
            .triggerHook(0)
            .addTo(controller);
        card_2 = new ScrollMagic.Scene({
            triggerElement: "#card-2 .spacer",
            duration: $("#card-2").height()
        })
            .triggerHook(0)
            .addTo(controller);
        footer = new ScrollMagic.Scene({
            triggerElement: "#section-7",
            duration: $("#section-7").height()
        }).setClassToggle("#section-7", 'show')
            // .triggerHook(0)
            .addTo(controller);
    }

}

function destroyScenes() {
    try {
        if (!needs_build) {
            needs_build = true;
            card_1.destroy(true);
            card_2.destroy(true);
            footer.destroy(true);
        }

    } catch (e) {

    }
}

if (!isMobile.phone) {
    (t = document.querySelector("section.call-to-action")) && new dt(t)


// build scene

}
$(window).on("resize", function (e) {
    if ($(window).width() < 768) {
        destroyScenes();
    } else {
        buildSecenes()
    }
});

