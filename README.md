# RedNoi

[![Build Status](https://travis-ci.org/vamind/rednoi.svg?branch=master)](https://travis-ci.org/vamind/rednoi)
[![Coverage Status](https://coveralls.io/repos/github/vamind/rednoi/badge.svg)](https://coveralls.io/github/vamind/rednoi)
[![License: MIT](https://img.shields.io/badge/License-MIT-blue.svg)](https://opensource.org/licenses/MIT)
[![Database: no](https://img.shields.io/badge/database-no-brightgreen.svg)](http://i0.kym-cdn.com/photos/images/newsfeed/000/617/851/56d.gif)

Overwhelmed? Let RedNoi help you to overcome your information overload. 

This tool is for advanced Twitter/TweetDeck users only, beginners might need to understand what Twitter/TweetDeck parameters are and how they work before using it.

RedNoi uses possibilities of [The Search API][] where you can use the filters on public twitter lists. The results are then combined and serialized by time into one stream. So the final result provides the one data stream, similiar to standard Twitter, but with filtered according to your specific needs.

You can set very specific levels of a filtering for a list:
- **min_retweets** ... minimum of retweets
- **min_faves** ... minimum of favourites
- **min_replies** ... minimum of replies
- **-filter:replies** ... a tweet is not a reply
- **lang:en** ... only in English
- **(Trump OR US) AND Kim** ... logic operators

Of course you can use it without a list (e.g.: *trump AND kim lang:en min_retweets:1000 min_faves:100*). 
You can use the search query from TweetDeck. For more information please check [The Search API][].

The best results are achieved when you create specific lists for each of reducing levels. Suppose we follow 3 different twitter users: our good friend, our favourite tech influencer and source of globals news. These users are totally different. Therefore It would be better to create 3 different lists.

* The friend - beneficial posts will need to have 1 like to appear.
* The influencer - beneficial posts will need to have 30 RT and 2 replies to appear.
* The news - beneficial posts will need to have 200 RT and 100 likes to appear. 

Then the queries might look like this:
* min_faves:1 list:your_twitter_name/friends -filter:replies
* min_retweets:30 min_replies:2 list:your_twitter_name/influencers -filter:replies
* min_replies:200 min_faves:100 list:your_twitter_name/news -filter:replies

Yes, this is the worst part. Because you need to find the reducing level for each of your source.

## Installation
RedNoi requires installed PHP 7.1+ and a web server.
```
git clone git@github.com:vamind/rednoi.git && cd rednoi/
composer update
npm install
gulp
```
Create your Twitter App at [Twitter Application Management][]. 
Fill only: Name, Description and Website (http://www.example.com). It will generate tokens and keys. 
In your favourite IDE open *app/config/parameters.yml* and copy&paste the parameters.

The **twitter.queries** accepts a list of lists/queries in same format as TweetDeck supports:
```
twitter.queries:
    - 'min_faves:1 list:your_twitter_name/friends -filter:replies'
    - 'min_retweets:30 min_replies:2 list:your_twitter_name/influencers -filter:replies'
    - 'min_replies:200 min_faves:100 list:your_twitter_name/news -filter:replies'
```

In the end you should add your Twitter nickname:
```
twitter.me: '* from:your_twitter_nickname'
```
[Twitter Application Management]: https://apps.twitter.com/
[Stack Overflow]: https://stackoverflow.com
[The Search API]: https://dev.twitter.com/rest/public/search
