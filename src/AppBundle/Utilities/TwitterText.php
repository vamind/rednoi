<?php declare(strict_types=1);

namespace AppBundle\Utilities;

class TwitterText
{
    public static function timeSince(string $time): string
    {
        $since = time() - strtotime($time);

        $chunks = [
            [31536000, 'year'],
            [2592000, 'month'],
            [604800, 'week'],
            [86400, 'day'],
            [3600, 'hour'],
            [60, 'minute'],
            [1, 'second'],
        ];

        $count = 0;
        $name = '';

        for ($i = 0; $i < 7; ++$i) {
            $seconds = $chunks[$i][0];
            $name = $chunks[$i][1];
            if (($count = (int) floor($since / $seconds)) !== 0) {
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
        $text = preg_replace('/([^\/])@([^\s\,\.\!\?\)\”\"]+[^\s\,\!\?\)\.\”\"]+)/', '\1<a href="https://twitter.com/\2" class="at" target="_blank">@</a><a href="https://twitter.com/\2" target="_blank">\2</a>', $text);
        $text = preg_replace('/^@([^\s\,\.\!\?\)\”\"]+[^\s\,\!\?\)\.\”\"]+)/', '<a href="https://twitter.com/\1" class="at" target="_blank">@</a><a href="https://twitter.com/\1" target="_blank">\1</a>', $text);
        $text = preg_replace('/(?<!href=\")(http:\/\/|https:\/\/)(www\.)?([^\s\”\"]+)([^\s\”\"\.\,\!\-\)\(]+)/', '<a href="\1\2\3\4" target="_blank">\3\4</a>', $text);

        $text = preg_replace('/utm[_a-z0-9=%]+&?/', '', $text);
        $text = preg_replace('/[\/\?\#\&]\" target=/', '" target=', $text);
        $text = preg_replace('/[\/\?\#\&]\<\/a>/', '</a>', $text);

        return $text;
    }
}
