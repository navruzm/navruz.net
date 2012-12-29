$(function() {
    $(window).scroll(function() {
        if ($(this).scrollTop() != 0) {
            $('#go-top').fadeIn();
        } else {
            $('#go-top').fadeOut();
        }
    });

    $('#go-top').click(function() {
        $('body,html').animate({scrollTop:0}, 800);
    });
});