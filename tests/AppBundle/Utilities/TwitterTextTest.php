<?php declare(strict_types=1);

namespace Tests\AppBundle\Utilities;

use AppBundle\Utilities\TwitterText;
use DateInterval;
use DateTime;
use PHPUnit\Framework\TestCase;

final class TwitterTextTest extends TestCase
{
    /**
     * @dataProvider tweetProvider
     */
    public function testProcessTweet(string $tweet, bool $retweet, string $expected): void
    {
        $result = TwitterText::processTweet($tweet, $retweet);
        $this->assertSame($expected, $result);
    }

    /**
     * @return mixed[]
     */
    public function tweetProvider(): array
    {
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

            ['https://www.rednoi.net/?utm_medium=flow&red=true&article_id=5456#omg&utm_medium=email', FALSE, '<a href="https://www.rednoi.net/?red=true&article_id=5456#omg" target="_blank">rednoi.net/?red=true&article_id=5456#omg</a>'],
            ['https://www.rednoi.net/?articleid=1235&utm_medium=flow&utm_medium=email', FALSE, '<a href="https://www.rednoi.net/?articleid=1235" target="_blank">rednoi.net/?articleid=1235</a>'],
            ['https://www.rednoi.net/?utm_medium=flow&articleid=1235&utm_medium=email', FALSE, '<a href="https://www.rednoi.net/?articleid=1235" target="_blank">rednoi.net/?articleid=1235</a>'],
            ['https://www.rednoi.net/?utm_medium=encoding%20space%20works&encoding=works%20also', FALSE, '<a href="https://www.rednoi.net/?encoding=works%20also" target="_blank">rednoi.net/?encoding=works%20also</a>'],

            ['abc @user abc', FALSE, 'abc <a href="https://twitter.com/user" class="at" target="_blank">@</a><a href="https://twitter.com/user" target="_blank">user</a> abc'],
            ['.@user. abc', FALSE, '.<a href="https://twitter.com/user" class="at" target="_blank">@</a><a href="https://twitter.com/user" target="_blank">user</a>. abc'],
            ['@user.abc', FALSE, '<a href="https://twitter.com/user" class="at" target="_blank">@</a><a href="https://twitter.com/user" target="_blank">user</a>.abc'],
        ];
    }

    /**
     * @dataProvider timeProvider
     */
    public function testTimeSince(string $time, string $expected): void
    {
        $result = TwitterText::timeSince($time);
        $this->assertSame($expected, $result);
    }

    /**
     * @return mixed[]
     */
    public function timeProvider(): array
    {
        $datePrototype = new DateTime;
        $times = [
            '16 hours' => '16 hours ago',
            '1 day' => '1 day ago',
            '2 days' => '2 days ago',
            '6 months' => '6 months ago',
            '7 years' => '7 years ago',
        ];

        $output = [];

        foreach ($times as $shift => $expectation) {
            $date = clone $datePrototype;
            $date->sub(DateInterval::createFromDateString($shift));
            $output[] = [$date->format('D M j H:i:s +0000 Y'), $expectation];
        }

        return $output;
    }
}
