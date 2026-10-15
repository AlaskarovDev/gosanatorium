"use strict";

/*Activate default tab contents*/
var $magicLine, defaultActive;

defaultActive = $('.tourex-tabs-menu li.active a').attr('href');
$(defaultActive).show();

$('.tourex-tabs-menu').append("<li id='magic-line'></li>");
$magicLine = $('#magic-line');
$magicLine.width($('.active').width())

$('.tourex-tabs-menu li a').on("click", function () {
    var $this, tabId, leftVal, $tabContent;
    $this = $(this);
    $tabContent = $('.tabContent');
    $this.parent().addClass('active').siblings().removeClass('active');
    tabId = $this.attr('href');

    leftVal = $($tabContent).index($(tabId)) * $tabContent.width() * -1;
    $('.tourex-tabs-wrapper').stop().animate({ left: leftVal });

    $magicLine
        .data('origLeft', $this.position().left)
        .data('origWidth', $this.width() + 40);
    return false;
});
