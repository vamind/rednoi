<?php

namespace tests\AppBundle\Utilities\TwitterText;

use AppBundle\Utilities\TwitterText;

class TwitterTextTest extends \PHPUnit_Framework_TestCase {

    /**
     * @dataProvider tweetProvider
     */
    public function testProcessTweet($tweet, $retweet, $expected) {
        $result = TwitterText::processTweet($tweet, $retweet);
        $this->assertEquals($expected, $result);
    }
/*
if (!$retweet) {
$text = preg_replace('/https?:\/\/t\.co\/[^\s]+/', '', $text);
$text = preg_replace('/([^\/])@([^\s\,\!\?\)\”]+[^\s\,\!\?\)\.\”\"]+)/', '\1<a href="https://twitter.com/\2" class="at" target="_blank">@</a><a href="https://twitter.com/\2" target="_blank">\2</a>', $text);
}

$text = preg_replace('/([\s\:])+(http:\/\/|https:\/\/)(www\.)?([^\s]+)/', '\1<a href="\2\3\4" target="_blank">\4</a>', $text);
*/
    public function tweetProvider() {
        return [
            ['', FALSE, ''],
            ['http://www.rednoi.net', FALSE, '<a href="http://www.rednoi.net" target="_blank">rednoi.net</a>'],
            ['http://rednoi.net', FALSE, '<a href="http://rednoi.net" target="_blank">rednoi.net</a>'],
            [',http://www.rednoi.net', FALSE, ',<a href="http://www.rednoi.net" target="_blank">rednoi.net</a>'],
            ['.http://www.rednoi.net', FALSE, '.<a href="http://www.rednoi.net" target="_blank">rednoi.net</a>'],
            ['?http://www.rednoi.net', FALSE, '?<a href="http://www.rednoi.net" target="_blank">rednoi.net</a>'],
            ['!http://www.rednoi.net', FALSE, '!<a href="http://www.rednoi.net" target="_blank">rednoi.net</a>'],
            ['"http://www.rednoi.net', FALSE, '"<a href="http://www.rednoi.net" target="_blank">rednoi.net</a>'],
            ['”http://www.rednoi.net', FALSE, '”<a href="http://www.rednoi.net" target="_blank">rednoi.net</a>'],
            ['http://www.rednoi.net/"', FALSE, '<a href="http://www.rednoi.net/" target="_blank">rednoi.net/</a>"'],
            ['http://www.rednoi.net,', FALSE, '<a href="http://www.rednoi.net" target="_blank">rednoi.net</a>,'],
            ['http://www.rednoi.net.', FALSE, '<a href="http://www.rednoi.net" target="_blank">rednoi.net</a>.'],
            [' http://www.rednoi.net', FALSE, ' <a href="http://www.rednoi.net" target="_blank">rednoi.net</a>'],
            ['abc http://www.rednoi.net abc', FALSE, 'abc <a href="http://www.rednoi.net" target="_blank">rednoi.net</a> abc'],
            ['https://www.rednoi.net', FALSE, '<a href="https://www.rednoi.net" target="_blank">rednoi.net</a>'],
            [' https://www.rednoi.net', FALSE, ' <a href="https://www.rednoi.net" target="_blank">rednoi.net</a>'],
            ['http://www.rednoi.net/default?a=1&b=2', FALSE, '<a href="http://www.rednoi.net/default?a=1&b=2" target="_blank">rednoi.net/default?a=1&b=2</a>'],
            ['https://www.rednoi.net/default?a=1&b=2', FALSE, '<a href="https://www.rednoi.net/default?a=1&b=2" target="_blank">rednoi.net/default?a=1&b=2</a>'],
            ['https://medium.com/@petrvacha/rednoi', FALSE, '<a href="https://medium.com/@petrvacha/rednoi" target="_blank">medium.com/@petrvacha/rednoi</a>'],
            ['http://www.rednoi.net http://www.rednoi.net', FALSE, '<a href="http://www.rednoi.net" target="_blank">rednoi.net</a> <a href="http://www.rednoi.net" target="_blank">rednoi.net</a>'],
        ];
    }

}