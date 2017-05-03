<?php

namespace AppBundle\Utilities;

class TwitterText {

    /**
     * @param string $time
     * @return string
     */
    public static function timeSince(string $time) : string {
        $since = time() - strtotime($time);

        $chunks = array(
            array(60 * 60 * 24 * 365 , 'year'),
            array(60 * 60 * 24 * 30 , 'month'),
            array(60 * 60 * 24 * 7, 'week'),
            array(60 * 60 * 24 , 'day'),
            array(60 * 60 , 'hour'),
            array(60 , 'minute'),
            array(1 , 'second')
        );

        for ($i = 0, $j = count($chunks); $i < $j; $i++) {
            $seconds = $chunks[$i][0];
            $name = $chunks[$i][1];
            if (($count = floor($since / $seconds)) != 0) {
                break;
            }
        }

        $string = ($count == 1) ? '1 ' . $name . ' ago' : $count . ' ' . $name . 's ago';

        return $string;
    }

    /**
     * @param $text
     * @param bool|FALSE $retweet
     * @return string
     */
    public static function processTweet($text, $retweet = FALSE) : string {
        if (!$retweet) {
            $text = preg_replace('/https?:\/\/t\.co\/[^\s]+/', '', $text);
            $text = preg_replace('/([^\/])@([^\s\,\!\?\)\”]+[^\s\,\!\?\)\.\”]+)/', '\1<a href="https://twitter.com/\2" class="at" target="_blank">@</a><a href="https://twitter.com/\2" target="_blank">\2</a>', $text);
        }

        $text = preg_replace('/([\s\:])+(http:\/\/|https:\/\/)(www\.)?([^\s]+)/', '\1<a href="\2\3\4" target="_blank">\4</a>', $text);
        return $text;
    }
}