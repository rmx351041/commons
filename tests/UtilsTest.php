<?php

class UtilsTest extends PHPUnit_Framework_TestCase
{
    public function testUnCamelize()
    {
        $result = \rmx351\commons\Util\Utils::unCamelize('userInfo');
        var_dump($result);
    }

    public function testCamelize()
    {
        $result = \rmx351\commons\Util\Utils::camelize('user_info', true);
        var_dump($result);
    }
}