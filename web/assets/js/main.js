$(document).ready(function () {
    $('.tweetText').each(function(){
        $(this).html(twemoji.parse($(this).html()));
    });
});

