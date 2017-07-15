$( document ).ready(function() {

    var currentUrl = window.location.origin + window.location.pathname;
        $('.js-retweet').on('click', function(e){
        e.preventDefault();
        var _this = $(this);
        var id = $(this).data('id');
        var data = {id : id};
        if ($(this).hasClass('active')) {
            $.post(currentUrl + "/unretweet", data)
                .done(function(tweet) {
                    var retweet_count = tweet['retweeted'] ? tweet['retweet_count'] - 1 : tweet['retweet_count'];
                    _this.find('.js-retweet-count').html(retweet_count);
                    _this.find('.js-like-count').html(tweet['favorite_count']);
                    _this.removeClass('active');
                });
        } else {
            $.post(currentUrl + "/retweet", data)
                .done(function(tweet) {
                    _this.find('.js-retweet-count').html(tweet['retweet_count']);
                    _this.find('.js-like-count').html(tweet['favorite_count']);
                    _this.addClass('active');
                });
        }
    });

    $('.js-like').on('click', function(e){
        e.preventDefault();
        var _this = $(this);
        var id = $(this).data('id');
        var data = {id : id};
        if ($(this).hasClass('active')) {
            $.post(currentUrl + "/unlike", data)
                .done(function (tweet) {
                    _this.find('.js-retweet-count').html(tweet['retweet_count']);
                    _this.find('.js-like-count').html(tweet['favorite_count']);
                    _this.removeClass('active');
                });
        } else {
            $.post(currentUrl + "/like", data)
                .done(function (tweet) {
                    _this.find('.js-retweet-count').html(tweet['retweet_count']);
                    _this.find('.js-like-count').html(tweet['favorite_count']);
                    _this.addClass('active');
                });
        }
    });
});