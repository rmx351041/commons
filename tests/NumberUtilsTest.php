<?php
use rmx351\commons\Money\Currency;

class NumberUtilsTest extends PHPUnit_Framework_TestCase
{
    public function testFormatCurrency()
    {
        $result = \rmx351\commons\Util\NumberUtils::formatCurrency('212.000');
        print_r($result);
    }

    public function testFormatPercent()
    {
        $result = \rmx351\commons\Util\NumberUtils::formatPercent('0.0000001');
        print_r($result);
    }

    public function testToChinese()
    {
        $result = \rmx351\commons\Util\NumberUtils::toChinese('12222222222.0001');
        print_r($result);
    }

    public function testToCent()
    {
        $result = \rmx351\commons\Util\NumberUtils::toCent('-12222222222.2101');
        print_r($result);
    }
}