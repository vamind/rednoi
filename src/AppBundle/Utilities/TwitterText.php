<?php declare(strict_types=1);

namespace AppBundle\Utilities;

class TwitterText
{
    public static function timeSince(string $time): string
    {
        $since = time() - strtotime($time);

        $chunks = [
            [60 * 60 * 24 * 365, 'year'],
            [60 * 60 * 24 * 30, 'month'],
            [60 * 60 * 24 * 7, 'week'],
            [60 * 60 * 24, 'day'],
            [60 * 60, 'hour'],
            [60, 'minute'],
            [1, 'second'],
        ];

        for ($i = 0, $j = count($chunks); $i < $j; ++$i) {
            $seconds = $chunks[$i][0];
            $name = $chunks[$i][1];
            if (($count = floor($since / $seconds)) !== 0) {
                break;
            }
        }

        $string = ($count === 1) ? '1 ' . $name . ' ago' : $count . ' ' . $name . 's ago';

        return $string;
    }

    public static function processTweet(string $text, bool $quoted = FALSE): string
    {
        if ($quoted) {
            $text = preg_replace('/https:\/\/twitter\.com\/.+\/status\/[0-9]+$/', '', $text);
        }

        $text = preg_replace('/https?:\/\/t\.co\/[^\s]+/', '', $text);
        $text = preg_replace('/([^\/])@([^\s\,\!\?\)\”\"]+[^\s\,\!\?\)\.\”\"]+)/', '\1<a href="https://twitter.com/\2" class="at" target="_blank">@</a><a href="https://twitter.com/\2" target="_blank">\2</a>', $text);
        $text = preg_replace('/(?<!href=\")(http:\/\/|https:\/\/)(www\.)?([^\s\”\"]+)([^\s\”\"\.\,\!\-\)\(]+)/', '<a href="\1\2\3\4" target="_blank">\3\4</a>', $text);

        $text = preg_replace('/utm[_a-z0-9=%]+&?/', '', $text);
        $text = preg_replace('/[\/\?\#\&]\" target=/', '" target=', $text);
        $text = preg_replace('/[\/\?\#\&]\<\/a>/', '</a>', $text);

        return $text;
    }
}
