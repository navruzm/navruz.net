$(document).ready(function() {
    closeable_divs(".closeable");
    /*$('tbody tr:even').addClass("alt-row");*/
    /*awesome button*/
    $('.awesome,.awesome:visited')
    .css('border-radius', '5px')
    .css('-moz-border-radius', '5px')
    .css('-webkit-border-radius', '5px')
    .css('-moz-box-shadow', '0 1px 3px rgba(0, 0, 0, 0.5)')
    .css('-webkit-box-shadow', '0 1px 3px rgba(0, 0, 0, 0.5)')
    .css('text-shadow', '0 -1px 1px rgba(0, 0, 0, 0.25)');
    /*.css('border-bottom', '1px solid rgba(0, 0, 0, 0.25)');*/
    $('.quote').click(
        function () {
            var item = $(this);
            var qu = item.parent().next().text();
            var qc = item.parent().parent().next().children('.post').text().trim();
            var qd = item.parent().parent().next().children('.date').text();
            var textarea = $('textarea#content');
            textarea.val(textarea.val() + '[quote name="'+ qu +'" date="'+ qd +'"]' + qc +'[/quote]');
            $.scrollTo('#quick-reply', {
                duration:1000
            });
            return false;
        });
    $('.gotop').click(
        function () {
            $.scrollTo('#header', {
                duration:1000
            });
        });
});

function closeable_divs(element) {
    $(element).each(
        function() {
            $(this).append('<div class="close"></div>')
        });
    $(".close").click(
        function () {
            $(this).parent().fadeTo(300, 0, function () {
                $(this).slideUp(50);
            });
            return false;
        });
}

function show_status(message)
{
    response = $("#response");
    if (typeof response == "undefined")
    {
        response = $("<div id='response'><"+"/div>").appendTo(document.body).css('opacity','.8').show();
    }
    response.html('<span>' + message + '<'+'/span>');
    response.html(message);
    response.show();
    response.find('span').animate({
        dummy: 1
    }, 3000).fadeTo(300, 0, function () {
        $(this).slideUp(100);
    });
}

function facebook_like(url)
{
    response = $("#like-box");
    if (typeof response != "undefined")
    {
        response.html('<iframe src="http://www.facebook.com/plugins/like.php?href='+url+'&amp;layout=standard&amp;show_faces=true&amp;width=450&amp;action=like&amp;colorscheme=light&amp;height=80" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:440px; height:80px;" allowTransparency="true"></iframe>');
    }
}
