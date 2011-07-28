$(document).ready(function() {
    $('.list tbody tr:even').addClass("alt-row");
    $("#side-nav li ul").hide();
    $("#side-nav li a.current").parent().find("ul").css("display", "block");
    $("#side-nav li a.side-nav-item").click(
            function () {
                $(this).parent().siblings().find("ul").slideUp("normal");
                $(this).next().slideToggle("normal");
                return false;
            }
            );
});