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

    /** @var array $me */
    private $me;

    /** @var \TwitterAPIExchange */
    private $twitter;


    /**
     * @param array $twitterAccess
     * @param array $twitterQueries
     * @param string $twitterMe
     */
    public function __construct(array $twitterAccess, array $twitterQueries, string $twitterMe) {
        $this->access = $twitterAccess;
        $this->queries = $twitterQueries;
        $this->me = $twitterMe;
        $this->twitter = new \TwitterAPIExchange($this->access);
    }

    /**
     * @param $endPoint
     * @param $id
     * @return array
     * @throws \Exception
     */
    protected function callPost($endPoint, $id) : array {
        $string = json_decode(
            $this->twitter
                ->buildOauth($endPoint, "POST")
                ->setPostfields(['id' => $id])
                ->performRequest(),$assoc = TRUE);

        return $string;
    }

    /**
     * @param $endPoint
     * @return array
     * @throws \Exception
     */
    protected function callGet($endPoint, $query) : array {
        return json_decode($this->twitter
            ->setGetfield('?q=' . $query . '&tweet_mode=extended')
            ->buildOauth($endPoint, "GET")
            ->performRequest(),$assoc = TRUE);
    }

    /**
     * @param $id
     * @return mixed
     * @throws \Exception
     */
    public function retweet($id) : array {
        $endPoint = "https://api.twitter.com/1.1/statuses/retweet/" . $id . ".json";
        return $this->callPost($endPoint, $id);
    }

    /**
     * @param $id
     * @return mixed
     * @throws \Exception
     */
    public function unretweet($id) : array {
        $endPoint = "https://api.twitter.com/1.1/statuses/unretweet/" . $id . ".json";
        return $this->callPost($endPoint, $id);
    }

    /**
     * @param $id
     * @return mixed
     * @throws \Exception
     */
    public function like($id) : array {
        $endPoint = "https://api.twitter.com/1.1/favorites/create.json";
        return $this->callPost($endPoint, $id);
    }

    /**
     * @param $id
     * @return mixed
     * @throws \Exception
     */
    public function unlike($id) : array {
        $endPoint = "https://api.twitter.com/1.1/favorites/destroy.json";
        return $this->callPost($endPoint, $id);
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function getData() : array {
        $endPoint = "https://api.twitter.com/1.1/search/tweets.json";
        $mergedArray = [];
        $myRetweets = $this->getMyRetweets();
        $myLikes = $this->getMyLikes();

        foreach ($this->queries as $query) {
            $string = $this->callGet($endPoint, $query);

            foreach ($string['statuses'] as $i => $items) {
                $items['query'] = $query;
                $items['twitterAuthorLink'] = self::TWITTERCOM . $items['user']['screen_name'];
                $items['name'] = $items['user']['name'];
                $items['twitterName'] = $items['user']['screen_name'];
                $items['time'] = TwitterText::timeSince($items['created_at']);
                $items['authorProfilePicture'] = $items['user']['profile_image_url'];

                if (!isset($items['quoted_status']) && isset($items['retweeted_status']['quoted_status'])) {
                    $items['quoted_status'] = $items['retweeted_status']['quoted_status'];
                }

                if (isset($items['quoted_status'])) {
                    $items['retweetAuthorName'] = $items['quoted_status']['user']['name'];
                    $items['retweetAuthorTwitterName'] = $items['quoted_status']['user']['name'];
                    $items['retweetAuthorLink'] = self::TWITTERCOM . $items['quoted_status']['user']['screen_name'];
                    $items['retweetLink'] = self::TWITTERCOM . $items['retweetAuthorLink'] . "/status/" . $items['quoted_status']['id_str'];
                    if (isset($items['quoted_status']['entities']['media'])) {
                        $items['mediaTw'] = $items['quoted_status']['entities']['media'][0]["media_url_https"];
                    }

                    foreach ($items['quoted_status']['entities']['urls'] as $url) {
                        $items['quoted_status']['full_text'] = str_replace($url['url'], $url['expanded_url'], $items['quoted_status']['full_text']);
                    }

                    $items['quoted_status']['full_text'] = TwitterText::processTweet($items['quoted_status']['full_text']);
                    $items['quoted_status']['link'] = $items['retweetAuthorLink'] . "/status/" . $items['quoted_status']['id_str'];
                }

                if (isset($items['entities']['media'])) {
                    $items['media'] = $items['entities']['media'][0]['media_url'];
                }

                foreach ($items['entities']['urls'] as $url) {
                    $items['full_text'] = str_replace($url['url'], $url['expanded_url'], $items['full_text']);
                }

                if (isset($items['retweeted_status']['full_text'])) {
                    if (in_array($items['retweeted_status']['id'], $myRetweets)) {
                        $items['retweeted'] = TRUE;
                    }
                    if (in_array($items['retweeted_status']['id'], $myLikes)) {
                        $items['favorited'] = TRUE;
                    }

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
                    if (in_array($items['id'], $myRetweets)) {
                        $items['retweeted'] = TRUE;
                    }
                    if (in_array($items['id'], $myLikes)) {
                        $items['favorited'] = TRUE;
                    }
                    $items['RT'] = FALSE;
                }

                $items['full_text'] = TwitterText::processTweet($items['full_text'], TRUE);
                $mergedArray[$items['id']] = $items;
            }
        }
        krsort($mergedArray, SORT_NUMERIC);

        return $mergedArray;
    }

    /**
     * @return array
     */
    protected function getMyRetweets() : array {
        $endPoint = 'https://api.twitter.com/1.1/statuses/user_timeline.json';
        $string = $this->callGet($endPoint, $this->me);

        $retweets = [];
        foreach($string as $items) {
            if (isset($items['retweeted_status'])) {
                $retweets[] = $items['retweeted_status']['id'];
            }
        }
        return $retweets;
    }

    /**
     * @return array
     */
    protected function getMyLikes() : array {
        $endPoint = 'https://api.twitter.com/1.1/favorites/list.json';
        $string = $this->callGet($endPoint, $this->me);

        $favorited = [];
        foreach($string as $items) {
            $favorited[] = $items['id'];
        }
        return $favorited;
    }
}