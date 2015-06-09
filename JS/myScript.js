function resizePage() {
    var windowHeight = $(window).height();

    if ($('.content').width() < 773)$('.content').width(773);

    if ($('.content').height() < windowHeight) {

        $('.content').height( windowHeight - $('footer').height() - 131);
    }

}
