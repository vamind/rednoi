$(document).ready(function () {
    $('.tweetText, .retweet p').each(function(){
        $(this).html(twemoji.parse($(this).html()));
    });
});

