// ============ Navigation ============
$(".nav-blacklayer").hide();

const hamburger = document.querySelector(".hamburger");
const navMenu = document.querySelector(".nav-menu");
hamburger.addEventListener("click", mobileMenu);
function mobileMenu() {
    hamburger.classList.toggle("active");
    navMenu.classList.toggle("active");
    $(".nav-blacklayer").toggle();
    $("body").toggleClass('overflow-hidden');
}

const navLink = document.querySelectorAll(".nav-link");
navLink.forEach(n => n.addEventListener("click", closeMenu));
function closeMenu() {
    hamburger.classList.remove("active");
    navMenu.classList.remove("active");
    $(".nav-blacklayer").hide();
    $("body").removeClass('overflow-hidden');
}

$(document).ready(function () {
    $('.close-nav').click(function () {
        hamburger.classList.remove("active");
        navMenu.classList.remove("active");
        $(".nav-blacklayer").hide();
        $("body").removeClass('overflow-hidden');
    });
    $('.nav-blacklayer').click(function () {
        hamburger.classList.remove("active");
        navMenu.classList.remove("active");
        $(".nav-blacklayer").hide();
        $("body").removeClass('overflow-hidden');
    });
});

// Scroll To Hide Company NAme
$(window).scroll(function () {
    if ($(this).scrollTop() > 100) {
        $('.scroll-hide').fadeOut();
    }
    else {
        $('.scroll-hide').fadeIn();
    }
});


$('.ShowPass').click(function () {
    if ($(this).hasClass('ri-eye-off-line')) {
        $(this).removeClass('ri-eye-off-line');
        $(this).addClass('ri-eye-line');
        $(this).parent().find('input').attr('type', 'text');
    } else {
        $(this).addClass('ri-eye-off-line');
        $(this).removeClass('ri-eye-line');
        $(this).parent().find('input').attr('type', 'password');

    }
});
$('#Supplier').click(function () {
    $('#DiamondTypeRadio').show();
});
$('#Buyer').click(function () {
    $('#DiamondTypeRadio').hide();
});


$('.selector-item_label').click(function () {
    $(this).parent('.selecotr-item').siblings().find('.selector-item_label').removeClass('SelectorActive');
    $(this).addClass('SelectorActive');

    var r = $(this).attr('href');
    $(r).addClass('abc').siblings().removeClass('abc')
});



// $(document).ready(function () {
//     if (window.matchMedia('(max-width: 767px)')) {
//         $('.readmore-content').parent('p').find('.hide-content').hide();

//         $('.readmore-content').click(function () {
//             var s = $(this).parent('p').find('.hide-content').toggleClass('d-inline')
//             console.log(s)

//             $(this).text(function (i, text) {
//                 return text === "Read More..." ? "Read Less" : "Read More...";
//             })
//         });
//     }
// })



