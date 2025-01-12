
let inputElements = $('[type=text],[type=email],[type=password],select,textarea');

$('form').on('submit', function () {
  if ($(this).valid()) {
    $(':submit', this).attr('disabled', 'disabled');
  }
});

$('.showFilterBtn').on('click', function () {
  $('.responsive-filter-card').slideToggle();
});

let heading;
Array.from(document.querySelectorAll('table')).forEach((table) => {
  heading = table.querySelectorAll('thead tr th');
  if (heading.length) {
    Array.from(table.querySelectorAll('tbody tr')).forEach((row) => {
      Array.from(row.querySelectorAll('td')).forEach((colum, i) => {
        colum.setAttribute('data-label', heading[i].innerText);
      });
    });
  }
});

setTimeout(function () {
  $('.cookies-card').removeClass('hide');
}, 2000);

var btn = $('.scrollToTop');

$(window).scroll(function () {
  if ($(window).scrollTop() > 300) {
    btn.addClass('active');
  } else {
    btn.removeClass('active');
  }
});

btn.on('click', function (e) {
  e.preventDefault();
  $('html, body').animate({ scrollTop: 0 }, '300');
});

$('.primary-menu-button').on('click', function () {
  $(this).toggleClass('active');
  $('.body-overlay').toggleClass('active');
  $('.mobile-menu').toggleClass('active');
});

// ========================== Header Hide Scroll Bar Js Start =====================
$('.wish-button, .primary-menu-button, .cart-button, .user-account-btn').on('click', function () {
  $('body').toggleClass('scroll-hide-sm');
});
$('.body-overlay').on('click', function () {
  $('body').removeClass('scroll-hide-sm');
});
// ========================== Header Hide Scroll Bar Js End =====================

$('.cart-button').on('click', function (e) {
  e.preventDefault();
  $('#cart-sidebar-area').addClass('active');
  $('.body-overlay').addClass('active');
});

$('.wish-button').on('click', function (e) {
  e.preventDefault(), $('#wish-sidebar-area').addClass('active'), $('.body-overlay').addClass('active');
});

$('.user-account-btn').on('click', function (e) {
  e.preventDefault();
  $('#authSidebarMenu').addClass('active');
  $('.body-overlay').addClass('active');
});

$('.user-dropdown-btn').on('click', function (e) {
  e.stopPropagation();
  $(this).siblings('.before-dropdown-menu').toggleClass('active');
});

$(document).on('click', function (e) {
  if (!$(e.target).is('.user-dropdown-btn') && !$(e.target).closest('.before-dropdown-menu').length) {
    $('.before-dropdown-menu').removeClass('active');
  }
});

//======== category dropdown js start here ========

$('.menu-category-btn').on('click', function (event) {
  event.stopPropagation();

  
  if ($('.banner-container .left-site-category').length){
    let isBannerCategoryMenuShowing = $('.banner-container .left-site-category')[0].clientWidth > 0;
  
    let isStickyHeader = $('.header-area').hasClass('active');
    if (!isStickyHeader && isBannerCategoryMenuShowing) {
      return;
    }
  }


  $('.category-dropdown-menu').toggleClass('show-category-dropdown');
});


$('body').on('click', function () {
  $('.category-dropdown-menu').removeClass('show-category-dropdown');
});

//======== category dropdown js end here ========

$('.body-overlay').on('click', function () {
  $('.site-sidebar').removeClass('active');
  removeOverlay()
});

$(document).on('click', '.sidebar-close-btn', function (e) {
  $(this).parents('.site-sidebar').removeClass('active');
  removeOverlay();
});

function removeOverlay() {
  $('.body-overlay').removeClass('active');
  $('body').removeClass('scroll-hide-sm');
}

function setBackgroundImage() {
  $('.bg--img').css('background-image', function () {
    return 'url(' + $(this).data('background') + ')';
  });
}

setBackgroundImage();

$('.header-search-btn').on('click', function () {
  $('.header-search-wrapper').toggleClass('show');
});

$('.search-close-btn').on('click', function () {
  $('.header-search-wrapper').removeClass('show');
})

$(window).on('scroll', function () {
  $(this).scrollTop() < 180 ? ($('.scrollToTop').removeClass('active'), $('.header-area').removeClass('active')) : ($('.scrollToTop').addClass('active'), $('.header-area').addClass('active'));
});
//======= form-check js =======

$('.counter-list__card').each(function () {
  $(this).isInViewport(function (status) {
    if (status === 'entered') {
      for (var i = 0; i < document.querySelectorAll('.odometer').length; i++) {
        var el = document.querySelectorAll('.odometer')[i];
        el.innerHTML = el.getAttribute('data-odometer-final');
      }
    }
  });
});


$(window).on('load', function () {
  $('.preloader').fadeOut(1000);

  $('.bg_img').css('background-image', function () {
    return 'url(' + $(this).data('background') + ')';
  });
});

if ("ontouchstart" in document.documentElement) {
  $('.product-thumb a').on('click', function (event) {
    event.preventDefault();
  });
}

$(".langSel").on("change", function () {
  window.location.href = "{{ route('home') }}/change/" + $(this).val();
});


var tooltipTriggerList = [].slice.call(document.querySelectorAll('[title], [data-title], [data-bs-title]'));
var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
  var tooltip = new bootstrap.Tooltip(tooltipTriggerEl);

  tooltipTriggerEl.addEventListener('click', function () {
    tooltip.hide();
  });
  return tooltip;
});

$('#confirmationModal').find('.btn--primary').removeClass('btn--primary').addClass('btn--base btn--sm');
$('#confirmationModal').find('.btn--dark').addClass('btn--sm');


if ("ontouchstart" in document.documentElement) {
$('.menu-item-arrowicon').on('click', function (e) {
  e.stopPropagation();
  e.preventDefault();
  $(this).parents('.fluid-menu').trigger('mouseover');
});

$('.fluid-menu').on('mouseover', function () {
  $(this).find('.categories__mega-menu').first().css({opacity:1, visibility: "visible", top:0});
});

$('.fluid-menu').on('mouseleave', function () {
  $(this).find('.categories__mega-menu').css({
    opacity: 0,
    visibility: 'hidden',
  });
});
}
