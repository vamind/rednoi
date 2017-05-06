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

    public function tweetProvider() {
        return [
            ['', FALSE, ''],
            ['abc abc', FALSE, 'abc abc'],
            ['http://www.rednoi.net', FALSE, '<a href="http://www.rednoi.net" target="_blank">rednoi.net</a>'],
            ['http://rednoi.net', FALSE, '<a href="http://rednoi.net" target="_blank">rednoi.net</a>'],
            [',http://www.rednoi.net', FALSE, ',<a href="http://www.rednoi.net" target="_blank">rednoi.net</a>'],
            ['.http://www.rednoi.net', FALSE, '.<a href="http://www.rednoi.net" target="_blank">rednoi.net</a>'],
            ['?http://www.rednoi.net', FALSE, '?<a href="http://www.rednoi.net" target="_blank">rednoi.net</a>'],
            ['!http://www.rednoi.net', FALSE, '!<a href="http://www.rednoi.net" target="_blank">rednoi.net</a>'],
            ['"http://www.rednoi.net', FALSE, '"<a href="http://www.rednoi.net" target="_blank">rednoi.net</a>'],
            ['”http://www.rednoi.net', FALSE, '”<a href="http://www.rednoi.net" target="_blank">rednoi.net</a>'],
            ['http://www.rednoi.net,', FALSE, '<a href="http://www.rednoi.net" target="_blank">rednoi.net</a>,'],
            ['http://www.rednoi.net.', FALSE, '<a href="http://www.rednoi.net" target="_blank">rednoi.net</a>.'],
            [' http://www.rednoi.net', FALSE, ' <a href="http://www.rednoi.net" target="_blank">rednoi.net</a>'],
            ['abc http://www.rednoi.net abc', FALSE, 'abc <a href="http://www.rednoi.net" target="_blank">rednoi.net</a> abc'],
            ['https://www.rednoi.net', FALSE, '<a href="https://www.rednoi.net" target="_blank">rednoi.net</a>'],
            [' https://www.rednoi.net', FALSE, ' <a href="https://www.rednoi.net" target="_blank">rednoi.net</a>'],
            ['http://www.rednoi.net/default?a=1&b=2', FALSE, '<a href="http://www.rednoi.net/default?a=1&b=2" target="_blank">rednoi.net/default?a=1&b=2</a>'],
            ['https://www.rednoi.net/default?a=1&b=2', FALSE, '<a href="https://www.rednoi.net/default?a=1&b=2" target="_blank">rednoi.net/default?a=1&b=2</a>'],

            ['https://medium.com/@user/rednoi', FALSE, '<a href="https://medium.com/@user/rednoi" target="_blank">medium.com/@user/rednoi</a>'],

            ['http://www.rednoi.net http://www.rednoi.net', FALSE, '<a href="http://www.rednoi.net" target="_blank">rednoi.net</a> <a href="http://www.rednoi.net" target="_blank">rednoi.net</a>'],

            ['https://t.co/Cz17jU4dAL', FALSE, ''],
            [' https://t.co/Cz17jU4dAL https://t.co/Cz17jU4dAL ', FALSE, '   '],
            [' https://bloom.bg/2qDikV9 https://t.co/dfBo5KFhvH ', FALSE, ' <a href="https://bloom.bg/2qDikV9" target="_blank">bloom.bg/2qDikV9</a>  '],


            ['https://twitter.com/BarackObama/status/859855649689174019', TRUE, ''],
        ];
    }

}