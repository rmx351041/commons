<?php
use Rmx351\Commons\Money\Currency;

class NumberUtilsTest extends PHPUnit_Framework_TestCase
{
    public function testFormatCurrency()
    {
        $result = \Rmx351\Commons\Util\NumberUtils::formatCurrency('212.000');
        print_r($result);
    }

    public function testFormatPercent()
    {
        $result = \Rmx351\Commons\Util\NumberUtils::formatPercent('0.0000001');
        print_r($result);
    }

    public function testToChinese()
    {
        $result = \Rmx351\Commons\Util\NumberUtils::toChinese('12222222222.0001');
        print_r($result);
    }

    public function testToCent()
    {
        $result = \Rmx351\Commons\Util\NumberUtils::toCent('-12222222222.2101');
        print_r($result);
    }
}