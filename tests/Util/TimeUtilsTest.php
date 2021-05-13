<?php

class TimeUtilsTest extends PHPUnit_Framework_TestCase
{
    public function testFormatDuration()
    {
        $result = \Rmx351\Commons\Util\TimeUtils::formatDuration('2020-1-1', '2020-2-1');
        print_r($result);
    }
}