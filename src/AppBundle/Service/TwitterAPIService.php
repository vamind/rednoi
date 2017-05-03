<?php


namespace AppBundle\Service;

use AppBundle\Utilities\TwitterText;


class TwitterAPIService {

    /** @const string */
    const TWITTERCOM = "https://twitter.com/";

    /** @var array $access */
    private $access;

    /** @var array $queries */
    private $queries;

    /**
     * @param array $twitterAccess
     * @param array $twitterQueries
     */
    public function __construct(array $twitterAccess, array $twitterQueries) {
        $this->access = $twitterAccess;
        $this->queries = $twitterQueries;
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function getData() : array {

        $twitter = new \TwitterAPIExchange($this->access);
        $endPoint = "https://api.twitter.com/1.1/search/tweets.json";
        $requestMethod = "GET";

        $mergedArray = [];

        foreach ($this->queries as $query) {
            $string = json_decode($twitter->setGetfield('?q=' . $query. '&tweet_mode=extended')
                ->buildOauth($endPoint, $requestMethod)
                ->performRequest(),$assoc = TRUE);


            foreach ($string['statuses'] as $i => $items) {
                $items['query'] = $query;
                $items['twitterAuthorLink'] = self::TWITTERCOM . $items['user']['screen_name'];
                $items['name'] = $items['user']['name'];
                $items['twitterName'] = $items['user']['screen_name'];
                $items['time'] = TwitterText::timeSince($items['created_at']);
                $items['authorProfilePicture'] = $items['user']['profile_image_url'];

                if (isset($items['quoted_status'])) {
                    $items['retweetAuthorName'] = $items['quoted_status']['user']['name'];
                    $items['retweetAuthorTwitterName'] = $items['quoted_status']['user']['name'];
                    $items['retweetAuthorLink'] = self::TWITTERCOM . $items['quoted_status']['user']['screen_name'];
                    $items['retweetLink'] = self::TWITTERCOM . $items['retweetAuthorLink'] . "/status/" . $items['quoted_status']['id_str'];
                    if (isset($items['quoted_status']['entities']['media'])) {
                        $items['mediaTw'] = $items['quoted_status']['entities']['media'][0]["media_url_https"];
                    }
                    $items['quoted_status']['full_text'] = TwitterText::processTweet($items['quoted_status']['full_text'], TRUE);
                    $items['quoted_status']['link'] = $items['retweetAuthorLink'] . "/status/" . $items['quoted_status']['id_str'];
                }

                if (isset($items['entities']['media'])) {
                    $items['media'] = $items['entities']['media'][0]['media_url'];
                }

                foreach ($items['entities']['urls'] as $url) {
                    $items['full_text'] = str_replace($url['url'], $url['expanded_url'], $items['full_text']);
                }

                if (isset($items['retweeted_status']['full_text'])) {
                    $items['full_text'] = $items['retweeted_status']['full_text'];
                    $items['RT'] = TRUE;
                    $items['originRetweetAuthorName'] = $items['retweeted_status']['user']['name'];
                    $items['originRetweetAuthorTwitterName'] = $items['retweeted_status']['user']['screen_name'];
                    $items['originRetweetAuthorLink'] = self::TWITTERCOM . $items['retweeted_status']['user']['screen_name'];

                    if (isset($items['retweeted_status']['entities']['media'])) {
                        $items['media'] = $items['retweeted_status']['entities']['media'][0]['media_url'];
                    }

                    foreach ($items['retweeted_status']['entities']['urls'] as $url) {
                        $items['full_text'] = str_replace($url['url'], $url['expanded_url'], $items['full_text']);
                    }
                } else {
                    $items['RT'] = FALSE;
                }
                $items['full_text'] = TwitterText::processTweet($items['full_text']);
                $mergedArray[$items['id']] = $items;
            }
        }
        krsort($mergedArray, SORT_NUMERIC);

        return $mergedArray;
    }
}