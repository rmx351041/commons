<?php

class ArrayUtilsTest extends PHPUnit_Framework_TestCase
{
    public function testDiff()
    {
        $result = \rmx351\commons\Util\ArrayUtils::diff([['id' => 1],['id' =>2]],[['id' => 1]]);
        print_r($result);
    }
}