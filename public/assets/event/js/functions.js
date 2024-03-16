"use strict";
var $window = $(window),
    $document = $(document),
    $this = $(this),
    $html = $("html"),
    $body = $("body");
function responsiveClasses() {
    jRespond([
        { label: "smallest", enter: 0, exit: 479 },
        { label: "handheld", enter: 480, exit: 767 },
        { label: "tablet", enter: 768, exit: 991 },
        { label: "laptop", enter: 992, exit: 1199 },
        { label: "desktop", enter: 1200, exit: 1e4 },
    ]).addFunc([
        {
            breakpoint: "desktop",
            enter: function () {
                $body.addClass("device-lg");
            },
            exit: function () {
                $body.removeClass("device-lg");
            },
        },
        {
            breakpoint: "laptop",
            enter: function () {
                $body.addClass("device-md");
            },
            exit: function () {
                $body.removeClass("device-md");
            },
        },
        {
            breakpoint: "tablet",
            enter: function () {
                $body.addClass("device-sm");
            },
            exit: function () {
                $body.removeClass("device-sm");
            },
        },
        {
            breakpoint: "handheld",
            enter: function () {
                $body.addClass("device-xs");
            },
            exit: function () {
                $body.removeClass("device-xs");
            },
        },
        {
            breakpoint: "smallest",
            enter: function () {
                $body.addClass("device-xxs");
            },
            exit: function () {
                $body.removeClass("device-xxs");
            },
        },
    ]);
}
function fullscreenSection() {
    $(".fullscreen").css("height", $(window).height());
}
$document.on("ready", function () {
    responsiveClasses(),
        fullscreenSection(),
        imageBG(),
        fitVideos(),
        BGVideoYTPlayer(),
        lightboxImage(),
        lightboxGallery(),
        lightboxIframe(),
        bannerParallaxImageBG(),
        sectionParallaxImageBG(),
        formCTASubscribe1(),
        scrollToAnchor(),
        counterStats(),
        sliderClients(),
        sliderTestimonials();
}),
    $window
        .on("resize", function () {
            responsiveClasses(), fullscreenSection();
        })
        .on("scroll", function () {
            scrollTopIcon();
        });
var carouselRtl,
    selectRtl,
    slickDirection,
    HTMLDir = $("html").css("direction");
function imageBG() {
    $(".img-bg").each(function () {
        var t = $(this),
            e = t.find("img").attr("src");
        t.parent(".section-image").length ? t.css("background-image", "url('" + e + "')") : (t.prepend("<div class='bg-element'></div>"), t.find(".bg-element").css("background-image", "url('" + e + "')")),
            t.find("img").css({ opacity: 0, visibility: "hidden" });
    });
}
function fitVideos() {
    $("#full-container").fitVids();
}
function BGVideoYTPlayer() {
    $(".video-background").each(function () {
        $(this).YTPlayer({ mute: !1, autoPlay: !0, opacity: 1, containment: ".video-background", showControls: !1, startAt: 0, addRaster: !0, showYTLogo: !1, stopMovieOnBlur: !1 });
    });
}
function parallaxStellar() {
    $(function () {
        ($body.hasClass("device-lg") || $body.hasClass("device-md") || $body.hasClass("device-sm")) && $.stellar({ horizontalScrolling: !1, verticalOffset: 0, responsive: !0 });
    });
}
function lightboxIframe() {
    $(".lightbox-iframe").magnificPopup({ type: "iframe", mainClass: "mfp-fade", removalDelay: 160, preloader: !1, fixedContentPos: !1 });
}
function lightboxImage() {
    $(".lightbox-img").magnificPopup({ type: "image", gallery: { enabled: !1 }, mainClass: "mfp-fade", removalDelay: 160, preloader: !1, fixedContentPos: !1 });
}
function lightboxGallery() {
    $(".lightbox-gallery").magnificPopup({ type: "image", gallery: { enabled: !0 }, mainClass: "mfp-fade", removalDelay: 160, preloader: !1, fixedContentPos: !1 });
}
function scrollTopIcon() {
    800 < $(window).scrollTop() ? $(".scroll-top-icon").addClass("show") : $(".scroll-top-icon").removeClass("show");
}
function bannerParallaxImageBG() {
    var t = $(".banner-parallax"),
        e = t.children("img:first-child").attr("src");
    t.prepend("<div class='bg-element'></div>"),
        t
            .find("> .bg-element")
            .css("background-image", "url('" + e + "')")
            .attr("data-stellar-background-ratio", 0.2);
}
function scrollToAnchor() {
    var t = $(".header-bar.sticky"),
        e = t.height(),
        i = t ? e : 0;
    $(".scroll-to").on("click", function (t) {
        t.preventDefault();
        t = $(this);
        $("html, body")
            .stop()
            .animate({ scrollTop: $(t.attr("href")).offset().top - i }, 800);
    });
}
function sectionParallaxImageBG() {
    $(".section-parallax").each(function () {
        var t = $(this),
            e = t.children("img:first-child").attr("src");
        t.prepend("<div class='bg-element'></div>"),
            t
                .find("> .bg-element")
                .css("background-image", "url('" + e + "')")
                .attr("data-stellar-background-ratio", 0.1);
    });
}
(slickDirection = "rtl" == HTMLDir ? ($("body").addClass("direction-rtl"), (selectRtl = "rtl"), (carouselRtl = !0)) : (selectRtl = carouselRtl = !1)),
    $(".scroll-top").on("click", function (t) {
        t.preventDefault(), $("html, body").animate({ scrollTop: 0 }, 1200);
    }),
    $(".banner-parallax").each(function () {
        var t = $(this).data("banner-height"),
            e = $(this).find(".row > [class*='col-']");
        $(this).css("min-height", t), $(e).css("min-height", t);
    });
var csNotifications = $(".cs-notifications");
function formCTASubscribe1() {
    $("#form-cta-subscribe-1").validate({ rules: { name: { required: !0, minlength: 3 }, email: { required: !0, email: !0 }, number: { required: !0, number: !0, minlength: 12, maxlength: 12 }, message: {required: !0} } });
    var t = csNotifications.data("error-msg"),
        e = t || "Please Follow Error Messages and Complete as Required";
    // $("#form-cta-subscribe-1").on("submit", function (t) {
    //     t.isDefaultPrevented() ? (cs1SubmitMSG(!1, '<i class="cs-error-icon fas fa-times"></i>' + e), cs1Error()) : (t.preventDefault(), cs1SubmitForm());
    // });
}
function cs1SubmitForm() {
    var t = $("#name").val(),
        e = $("#email").val(),
        i = $("#number").val();
    $.ajax({
        type: "POST",
        url: "./php/cs1-process.php",
        data: "cs1Name=" + t + "&cs1Email=" + e + "&cs1PhoneNum=" + i,
        success: function (t) {
            "success" == t ? cs1Success() : (cs1Error(), cs1SubmitMSG(!1, t));
        },
    });
}
function cs1Success() {
    var t = csNotifications.data("success-msg"),
        t = t || "Thank you for your submission :)";
    $("#form-cta-subscribe-1")[0].reset(),
        cs1SubmitMSG(!0, '<i class="cs-success-icon fas fa-check"></i>' + t),
        $(".cs-notifications-content").addClass("sent"),
        csNotifications.css("opacity", 0),
        csNotifications.slideDown(300).animate({ opacity: 1 }, 300).delay(5e3).slideUp(400),
        $("#form-cta-subscribe-1").hasClass("redirected") &&
            setTimeout(function () {
                window.location.href = "page-thank-you.html";
            }, 3e3);
}
function cs1Error() {
    csNotifications.css("opacity", 0), csNotifications.slideDown(300).animate({ opacity: 1 }, 300), $(".cs-notifications-content").removeClass("sent");
}
function cs1SubmitMSG(t, e) {
    var i = "shake animated";
    csNotifications
        .delay(300)
        .addClass(i)
        .one("webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend", function () {
            $(this).removeClass("shake bounce animated");
        }),
        csNotifications.children(".cs-notifications-content").html(e);
}
function counterStats() {
    $(".counter-stats").each(function () {
        var t = $(this),
            e = t.text(),
            i = e.toString().replace(/(\d)/g, "<span class='digit'><span class='digit-value'>$1</span></span>");
        t.html(i + "<div class='main'>" + e + "</span>"),
            t.find(".digit").each(function () {
                var t = $(this),
                    e = t.find(".digit-value").text();
                t.append("<div class='counter-animator' data-value='" + e + "'><ul><li>0</li><li>1</li><li>2</li><li>3</li><li>4</li><li>5</li><li>6</li><li>7</li><li>8</li><li>9</li></ul></div>");
            });
    }),
        $(".counter-stats").each(function (e) {
            var i = $(this);
            new Waypoint({
                element: i,
                handler: function (t) {
                    setTimeout(function () {
                        setTimeout(function () {
                            1 < $(window).width() &&
                                i.find(".counter-animator").each(function () {
                                    var t = $(this),
                                        e = 10 * t.data("value");
                                    t.find("ul").css({
                                        transform: "translateY(-" + e + "%)",
                                        "-webkit-transform": "translateY(-" + e + "%)",
                                        "-moz-transform": "translateY(-" + e + "%)",
                                        "-ms-transform": "translateY(-" + e + "%)",
                                        "-o-transform": "translateY(-" + e + "%)",
                                    });
                                });
                        }, 0 * e);
                    }, 0);
                },
                offset: "100%",
            });
        });
}
function sliderClients() {
    $(".slider-clients > .slick-slider").slick({
        slidesToShow: 6,
        slidesToScroll: 1,
        dots: !1,
        infinite: !0,
        autoplay: !0,
        rtl: slickDirection,
        arrows: !1,
        touchThreshold: 20,
        responsive: [
            { breakpoint: 1200, settings: { slidesToShow: 5 } },
            { breakpoint: 992, settings: { slidesToShow: 4 } },
            { breakpoint: 768, settings: { slidesToShow: 3 } },
            { breakpoint: 480, settings: { slidesToShow: 2 } },
        ],
    });
}
function sliderTestimonials() {
    $(".slider-testimonials > .slick-slider").slick({
        slidesToShow: 3,
        slidesToScroll: 1,
        dots: !1,
        infinite: !1,
        rtl: slickDirection,
        arrows: !1,
        touchThreshold: 20,
        responsive: [
            { breakpoint: 1400, settings: { slidesToShow: 3 } },
            { breakpoint: 1200, settings: { slidesToShow: 2 } },
            { breakpoint: 992, settings: { slidesToShow: 2 } },
            { breakpoint: 768, settings: { slidesToShow: 1 } },
            { breakpoint: 480, settings: { slidesToShow: 1 } },
        ],
    });
}
function switcherFire() {
    setTimeout(function () {
        $(".style-switcher").addClass("show"), $(".ss-icon").toggleClass("rotating");
    }, 2e3),
        $(".demos-preview li a").each(function () {
            var e = $(this);
            e.hover(function () {
                var t = e.find("img").attr("src");
                $(".dp-img").attr("src", t).toggleClass("appeared");
            });
        }),
        $(".ss-icon").on("click", function (t) {
            t.preventDefault(), $(".style-switcher").toggleClass("show"), $(this).toggleClass("rotating");
        }),
        $(".switch-button").each(function () {
            $(this).on("click", function () {
                $(this).toggleClass("active"), $(this).next().toggleClass("active"), $(this).prev().toggleClass("active");
            }),
                $(this)
                    .prev("span")
                    .on("click", function () {
                        $(this).addClass("active"), $(this).nextAll().removeClass("active");
                    }),
                $(this)
                    .next("span")
                    .on("click", function () {
                        $(this).addClass("active"), $(this).prev(".switch-button").addClass("active").prevAll("span:first-child").removeClass("active");
                    });
        }),
        $(".list-patterns li button").each(function () {
            $(this).on("click", function () {
                $("body").addClass("boxed"),
                    $(".slider-banner .slick-slider").slick("setPosition"),
                    $(".page-width-option .switch-button").addClass("active"),
                    $(".page-width-option .switch-button").next().addClass("active"),
                    $(".page-width-option .switch-button").prev().removeClass("active");
                var t = "bg-pattern-" + $(this).attr("class");
                $("body").removeClass(function (t, e) {
                    return (e.match(/(^|\s)bg-pattern-\S+/g) || []).join(" ");
                }),
                    $("body").addClass(t),
                    $(".list-patterns li button").removeClass("active"),
                    $(this).addClass("active");
            });
        }),
        $(".page-width-option .switch-button").each(function () {
            $("body").hasClass("boxed") && ($(this).addClass("active"), $(this).prev("span").removeClass("active"), $(this).next("span").addClass("active"), $(".list-patterns").removeClass("hidden")),
                $(this).on("click", function () {
                    $("body").toggleClass("boxed"), $(".slider-banner .slick-slider").slick("setPosition"), $("body").hasClass("boxed") && $("body").is("[class*='bg-pattern-']");
                }),
                $(this)
                    .prev("span")
                    .on("click", function () {
                        $("body").removeClass("boxed");
                    }),
                $(this)
                    .next("span")
                    .on("click", function () {
                        $("body").addClass("boxed");
                    });
        });
}

$("body").waitForImages({
    finished: function () {
        setTimeout(function () {
            $(".lp-content, #loading-progress .logo").css({ opacity: 0 }),
                $("#loading-progress").addClass("hide-it"),
                setTimeout(function () {
                    $(".banner-parallax > .bg-element, .banner-parallax > .overlay-pattern").addClass("appeared");
                }, 1500);
        }, 0);
    },
    each: function (t, e, i) {
        e = Math.floor(((t + 1) / e) * 100);
        $("#lp-counter").animate(
            { $this: e },
            {
                duration: 150,
                easing: "",
                queue: !1,
                step: function (t) {
                    t = Math.round(t);
                    $("#lp-counter").text(t + "%");
                },
            }
        ),
            $("#lp-bar").animate({ width: e + "%" }, 0);
    },
    waitForAll: !0,
}),
    $(".scroll-to").on("click", function (t) {
        t.preventDefault();
        t = $(this);
        $("html, body")
            .stop()
            .animate({ scrollTop: $(t.attr("href")).offset().top }, 1200);
    });
