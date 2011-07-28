$(document).ready(function() {
    $('pre').each(function() {
        $(this).before('<div class="code-head"><a href="#" class="code-switch">Düz metin olarak görüntüle</a></div>').after('<textarea rows="' + ($(this).children('code').html().split("\n").length+2) + '" cols="50" class="code">' + $(this).children('code').html() + '</textarea>');
        $(this).next().css('width',parseInt($(this).css('width'))-25);
    });
    $('.code-switch').toggle(function() {
        $(this).text('Renklendir').parent().next().hide().next().show();
    }, function() {
        $(this).text('Düz metin olarak görüntüle').parent().next().show().next().hide();
    });
});

