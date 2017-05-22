# RedNoi

Overwhelmed? Let RedNoi helps you to overcome your information overload. 

This tool is for advanced Twitter/TweetDeck users only, beginners might need to understand what Twitter/TweetDeck parameters are and how they work before using it.

Rednoi uses possibilities of [The Search API][] where you can use the filters on public twitter lists. The results are then combined and serialized by time into one stream. So the final result provides the one data stream same like the Twitter and with filtered data of your specific lists.

You can set very specific levels of a filtering for a list:
- **min_retweets** ... minimum of retweets
- **min_faves** ... minimum of favourites
- **min_replies** ... minimum of replies
- **-filter:replies** ... a tweet is not a replay
- **lang:en** ... only in English
- **(Trump OR US) AND Kim** ... logic operators

Of course you can use it without a list (e.g.: *trump AND kim lang:en min_retweets:1000 min_faves:100*). 
You can use the search query from TweetDeck. For more information please check [The Search API][].

The best results are achieved when you create specific lists for each reducing levels. Suppose We follow 3 different twitter users: our good friend, our favourite tech influencer and source of globals news. These users are totally different. Therefore It would be better to create 3 different lists.

* The friend - beneficial posts are rated for us from 1 like.
* The influencer - beneficial posts are rated for us from 30 RT and 2 replies.
* The news - beneficial posts are rated for us from 200 RT and 100 likes. 

Then the queries might look like this:
* min_faves:1 list:your_twitter_name/friends -filter:replies
* min_retweets:30 min_replies:2 list:your_twitter_name/influencers -filter:replies
* min_replies:200 min_faves:100 list:your_twitter_name/news -filter:replies

Yes, this is the worst part. Because you need to find the reducing level for each of your source.

## Installation
Sorry we are not a service (yet). RedNoi requires installed PHP 7.0+ and a web server. 
```
$ git clone git@github.com:vamind/rednoi.git && cd rednoi/
$ composer update
$ npm install
$ gulp
```
Create your Twitter App at [Twitter Application Management][]. 
Fill only: Name, Description and Website (http://www.whatever.com). It will generate tokens and keys. 
In your favourite IDE open *app/config/parameters.yml* and copy&paste the parameters.

The **twitter.queries** accepts a list of lists/queries in same format as TweetDeck supports:
```
twitter.queries:
    - 'min_faves:1 list:your_twitter_name/friends -filter:replies'
    - 'min_retweets:30 min_replies:2 list:your_twitter_name/influencers -filter:replies'
    - 'min_replies:200 min_faves:100 list:your_twitter_name/news -filter:replies'
```
[Twitter Application Management]: https://apps.twitter.com/
[Stack Overflow]: https://stackoverflow.com
[The Search API]: https://dev.twitter.com/rest/public/search
