<?php

namespace tests\AppBundle\Utilities\TwitterText;

use AppBundle\Utilities\TwitterText;

class TwitterTextTest extends \PHPUnit_Framework_TestCase {

    public function testProcessTweet() {
        $result = TwitterText::processTweet('');
        $this->assertEmpty($result);
    }

}