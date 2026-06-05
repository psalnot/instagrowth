/****** FILE: themes/site-template/js/jpreloader.js *****/
document.addEventListener( 'wheel', this.onMouseWheel, {passive: false} );
window.addEventListener( 'wheel', event => { event.preventDefault(); }, { passive: false } );
(function ($) {
    var items = new Array(), errors = new Array(), onComplete = function () {
    }, current = 0;
    var jpreOptions = {
        splashVPos: '35%',
        loaderVPos: '75%',
        splashID: '#jpreContent',
        showSplash: true,
        showPercentage: true,
        autoClose: true,
        closeBtnText: 'Start!',
        onetimeLoad: false,
        debugMode: false,
        splashFunction: function () {
        }
    }
    var getCookie = function () {
        if (jpreOptions.onetimeLoad) {
            var cookies = document.cookie.split('; ');
            for (var i = 0, parts; (parts = cookies[i] && cookies[i].split('=')); i++) {
                if ((parts.shift()) === "jpreLoader") {
                    return (parts.join('='));
                }
            }
            return false;
        } else {
            return false;
        }
    }
    var setCookie = function (expires) {
        if (jpreOptions.onetimeLoad) {
            var exdate = new Date();
            exdate.setDate(exdate.getDate() + expires);
            var c_value = ((expires == null) ? "" : "expires=" + exdate.toUTCString());
            document.cookie = "jpreLoader=loaded; " + c_value;
        }
    }
    var createContainer = function () {
        jOverlay = $('<div></div>').attr('id', 'jpreOverlay').css({
            position: "fixed",
            top: 0,
            left: 0,
            width: '100%',
            height: '100%',
            zIndex: 9999999
        }).appendTo('body');
        if (jpreOptions.showSplash) {
            jContent = $('<div></div>').attr('id', 'jpreSlide').appendTo(jOverlay);
            var conWidth = $(window).width() - $(jContent).width();
            $(jContent).css({
                position: "absolute",
                top: jpreOptions.splashVPos,
                left: Math.round((50 / $(window).width()) * conWidth) + '%'
            });
            $(jContent).html($(jpreOptions.splashID).wrap('<div/>').parent().html());
            $(jpreOptions.splashID).remove();
            jpreOptions.splashFunction()
        }
        jLoader = $('<div></div>').attr('id', 'jpreLoader').appendTo(jOverlay);
        var posWidth = $(window).width() - $(jLoader).width();
        $(jLoader).css({
            position: 'absolute',
            top: jpreOptions.loaderVPos,
            left: Math.round((50 / $(window).width()) * posWidth) + '%'
        });
        jBar = $('<div></div>').attr('id', 'jpreBar').css({width: '0%', height: '100%'}).appendTo(jLoader);
        if (jpreOptions.showPercentage) {
            jPer = $('<div></div>').attr('id', 'jprePercentage').css({
                position: 'relative',
                height: '100%'
            }).appendTo(jLoader).html('Loading...');
        }
        if (!jpreOptions.autoclose) {
            jButton = $('<div></div>').attr('id', 'jpreButton').on('click', function () {
                loadComplete();
            }).css({position: 'relative', height: '100%'}).appendTo(jLoader).text(jpreOptions.closeBtnText).hide();
        }
    }
    var getImages = function (element) {
        $(element).find('*:not(script)').each(function () {
            var url = "";
            if ($(this).css('background-image').indexOf('none') == -1 && $(this).css('background-image').indexOf('-gradient') == -1) {
                url = $(this).css('background-image');
                if (url.indexOf('url') != -1) {
                    var temp = url.match(/url\((.*?)\)/);
                    url = temp[1].replace(/\"/g, '');
                }
            } else if ($(this).get(0).nodeName.toLowerCase() == 'img' && typeof($(this).attr('src')) != 'undefined') {
                url = $(this).attr('src');
            }
            if (url.length > 0) {
                items.push(url);
            }
        });
    }
    var preloading = function () {
        for (var i = 0; i < items.length; i++) {
            if (loadImg(items[i]));
        }
    }
    var loadImg = function (url) {
        var imgLoad = new Image();
        $(imgLoad).load(function () {
            completeLoading();
        }).error(function () {
            errors.push($(this).attr('src'));
            completeLoading();
        }).attr('src', url);
    }
    var completeLoading = function () {
        current++;
        var per = Math.round((current / items.length) * 100);
        $(jBar).stop().animate({width: per + '%'}, 500, 'linear');
        if (jpreOptions.showPercentage) {
            $(jPer).text(per + "%");
        }
        if (current >= items.length) {
            current = items.length;
            setCookie();
            if (jpreOptions.showPercentage) {
                $(jPer).text("100%");
            }
            if (jpreOptions.debugMode) {
                var error = debug();
            }
            $(jBar).stop().animate({width: '100%'}, 500, 'linear', function () {
                if (jpreOptions.autoClose)
                    loadComplete(); else
                    $(jButton).fadeIn(1000);
            });
        }
    }
    var loadComplete = function () {
        $(jOverlay).fadeOut(800, function () {
            $(jOverlay).remove();
            onComplete();
        });
    }
    var debug = function () {
        if (errors.length > 0) {
            var str = 'ERROR - IMAGE FILES MISSING!!!\n\r'
            str += errors.length + ' image files cound not be found. \n\r';
            str += 'Please check your image paths and filenames:\n\r';
            for (var i = 0; i < errors.length; i++) {
                str += '- ' + errors[i] + '\n\r';
            }
            return true;
        } else {
            return false;
        }
    }
    $.fn.jpreLoader = function (options, callback) {
        if (options) {
            $.extend(jpreOptions, options);
        }
        if (typeof callback == 'function') {
            onComplete = callback;
        }
        $('body').css({'display': 'block'});
        return this.each(function () {
            if (!(getCookie())) {
                createContainer();
                getImages(this);
                preloading();
            }
            else {
                $(jpreOptions.splashID).remove();
                onComplete();
            }
        });
    };
})(jQuery);
;
/****** FILE: themes/site-template/js/jpreloader.js *****/
/****** FILE: themes/site-template/js/jpreloader.js *****/
/****** FILE: themes/site-template/js/jpreloader.js *****/
/****** FILE: themes/site-template/js/jpreloader.js *****/
/****** FILE: themes/site-template/js/jpreloader.js *****/




function sectionNav() {
    $('#section_nav').onePageNav({
        currentClass: 'current',
        changeHash: false,
        scrollSpeed: 850,
        scrollThreshold: 0.5,
        filter: '',
        easing: 'swing',
        end: function () {
            $("[data-toggle='tooltip']").tooltip('hide');
        }
    });
}

function fulllPage() {
    $("#HowItWorksPageClient, #HowItWorksPageModel").fullpage({
        autoScrolling: true,
        fitToSection: true,
        verticalCentered: true,
        scrollBar: true,
        scrollingSpeed: 900,
        fixedElements: '#right_navigation',
        //onLeave: function (index, nextIndex, direction) {
        //
        //    if (direction == 'down') {
        //        $imageSlides.css("background-attachment", "scroll");
        //        $imageSlides.eq(index- 2).css("background-attachment", "fixed");
        //
        //    }
        //    else {
        //        $imageSlides.css("background-attachment", "scroll");
        //        $imageSlides.eq(index - 1).css("background-attachment", "fixed");
        //
        //    }
        //   }
    });
}

if (($(window).width() == 768 || $(window).width() == 1024) && ($(window).width()>$(window).height() || $(window).height()>$(window).width()) ){
    window.addEventListener("orientationchange", function () {
        window.location.reload();

    }, false);
}

$( window ).resize(function() {
    if ($(window).width() <= 768) {


        $(".model-side, .client-side, .client-side-bg, .model-side-bg").css({
            "height": ($(window).height()-160)/2
        });

    }
    else {
        $("  .client-side, .model-side").css({
            "height": $(window).height()
        });
        $(".client-side-bg, .model-side-bg").css({
            "height": $(window).height()-130
        });


    }

});

if ($(window).width() <= 768) {

    $(".top-bg,  .top-section, #bottom-section, .bottom-bg").css({
        "height": $(window).height()
    });
    $(".model-side, .client-side, .client-side-bg, .model-side-bg").css({
        "height": ($(window).height()-160)/2
    });

    var wow = new WOW(
        {
            boxClass: 'wow',      // animated element css class (default is wow)
            animateClass: 'animated', // animation css class (default is animated)
            offset: 0,          // distance to the element when triggering the animation (default is 0)
            mobile: true,       // trigger animations on mobile devices (default is true)
            live: true,       // act on asynchronously loaded content (default is true)
            callback: function (box) {
                // the callback is fired every time an animation is started
                // the argument that is passed in is the DOM node being animated
            },
            scrollContainer: null // optional scroll container selector, otherwise use window
        }
    );
    wow.init();
}
else {
    $(" #HowItWorksPageClient, #HowItWorksPageModel, .client-side, .model-side").css({
        "height": $(window).height()
    });
    $(".client-side-bg, .model-side-bg").css({
        "height": $(window).height()-190
    });
    sectionNav();
    fulllPage();
    //var number_wow = new WOW(
    //    {
    //        boxClass: 'number-wow',      // animated element css class (default is wow)
    //        animateClass: 'animated', // animation css class (default is animated)
    //        offset: 0,          // distance to the element when triggering the animation (default is 0)
    //        mobile: true,       // trigger animations on mobile devices (default is true)
    //        live: true,       // act on asynchronously loaded content (default is true)
    //        callback: function (box) {
    //            // the callback is fired every time an animation is started
    //            // the argument that is passed in is the DOM node being animated
    //        },
    //        scrollContainer: null // optional scroll container selector, otherwise use window
    //    }
    //);
    //number_wow.init();

}

$('.bg-slider').slick({
    infinite: false,
    arrows: true,
    dots: true,
    customPaging: function (slider, i) {
        return '<span class="slider-dot"></span>';
    },
    slidesToShow: 1,
    slidesToScroll: 1,
    speed: 400,
    autoplay: true,
    fade: false,
    cssEase: 'linear',
    prevArrow: '<img class="slickArrow slickArrow-prev" src="themes/bootstrap/img/slider-prev.png"/>',
    nextArrow: '<img class="slickArrow slickArrow-next" src="themes/bootstrap/img/slider-next.png"/>',

});

//$('.bg-slider').on('afterChange', function (event, slick, currentSlide) {
//
//    if(currentSlide === 2) {
//        $('.slickArrow-next').addClass('hidden');
//    }
//    else {
//        $('.slickArrow-next').removeClass('hidden');
//    }
//
//    if(currentSlide === 0) {
//        $('.slickArrow-prev').addClass('hidden');
//    }
//    else {
//        $('.slickArrow-prev').removeClass('hidden');
//    }
//});

//$('.top-container h1').hide();
$('body').jpreLoader({
        splashID: "#loader-wrapper",
        autoClose: true,
        onetimeLoad: true,
        debugMode: false,
        showPercentage: false
    }
);

<!-- End of jPreLoader script -->



