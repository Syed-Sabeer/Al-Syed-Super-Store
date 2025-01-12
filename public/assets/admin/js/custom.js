'use strict';

function keepFirstLetterOfWords(input) {
  return input.split(' ').map(word => word.charAt(0)).join('');
}

function createSlug(inputString) {
  if (!inputString) return '';
  return inputString
    .trim()
    .toLowerCase()
    .replace(/[^\w\s-]/g, '')
    .replace(/[\s_-]+/g, '-')
    .replace(/^-+|-+$/g, '');
}

function generateSKU(brand, category, variant = null) {
  const brandPart = brand.substring(0, 3).toUpperCase();
  const categoryPart = category.substring(0, 3).toUpperCase();
  const variantPart = variant ? keepFirstLetterOfWords(variant) : '';
  const sku = `${brandPart}-${categoryPart}-${variantPart}`;
  return sku;
}

function appendAndShowElement(container, content, animate = true) {
  if (!animate) {
    return $(content).appendTo(container);
  }
  return $(content).appendTo(container).hide().slideDown('slow');
}

(function ($) {

  $.fn.showPreloader = function (i) {
    $(this).prepend(`<div class="ajax-preloader"></div>`);
  }

  $.fn.removePreloader = function (i) {
    $(this).find('.ajax-preloader').remove();
  }


})(jQuery);