var sticky_navigation_offset_top;

function resizePage() {

    var windowHeight = $(window).height();
    windowHeight -= ($('footer').height() + 132);

    $('.content').removeAttr('style');


    if ($('.content').width() < 773)$('.content').width(773);

    if ($('.content').height() < windowHeight) {

        $('.content').height(Math.round(windowHeight));
    }


}

function sticky_navigation() {
    var scroll_top = $(window).scrollTop();
    if (scroll_top > sticky_navigation_offset_top) {

        if ($('.container').offset()['left'] < 1)
            $('.sidebar1').css({'position': 'fixed', 'top': 0, 'left': -$(window).scrollLeft()});
        else
            $('.sidebar1').css({'position': 'fixed', 'top': 0, 'left': $('.container').offset()['left']});

    } else {

        $('.sidebar1').css({'position': 'relative', 'left': 0});
    }
}
