$(function(){

    $('.slider_inner, .news_slider-inner').slick({
        nextArrow: '<button type="button" class="slick-btn slick-next"></button>',
        prevArrow: '<button type="button" class="slick-btn slick-prev"></button>',
        infinite: false
    });
    $('.blog_slider_inner, .blog_slider-inner').slick({
        nextArrow: '<button type="button" class="slick-btn slick-next"></button>',
        prevArrow: '<button type="button" class="slick-btn slick-prev"></button>',
        infinite: false
    });
    $('.about_slider_inner, .about_slider-inner').slick({
        nextArrow: '<button type="button" class="slick-btn slick-next"></button>',
        prevArrow: '<button type="button" class="slick-btn slick-prev"></button>',
        infinite: false
    });
    $('select').styler();
    $('.header_btn-menu').on('click', function(){
        $('.menu ul').slideToggle();
    });
    $('').styler();
});
function showStuff(id) {
    document.getElementById(id).style.display = 'block';
    return false; 
}
function showClose(id) {
        document.getElementById(id).style.display = 'none';
        return false;
}
jQuery(document).ready(function($){
    var url = document.location.href;
    new Clipboard('.copy_link', {text: function(){ return url;}});
    $('.copy_link').click(function(){alert('Cсылка успешно скопирована в буфер обмена.');});
    });
