"use strict";
/**
 * @package   DPG_WP_EventApi
 */
jQuery(document).ready(function ($) {


    showCategories();

    $(".c-event-activities-category nav a").on('click', function (){
        $('.c-event-activities-category nav a').removeClass('active');
        $(this).addClass('active');

        showCategories();
    });

    $(".c-event-category .day .title").on('click', function (){
        $(this).siblings().toggle();
        $(this).toggleClass('open');
    });

    $(".c-event-category .day .activities .activity a").on('click', function (){
        $(this).siblings().toggleClass('open');
    });

    function showCategories() {
        let current_category = $(".c-event-activities-category nav a.active").data('category');
        $('.c-event-category').hide();
        $('.c-event-category[data-category="'+current_category+'"]').show();
    }
});

