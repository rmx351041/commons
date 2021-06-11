<?php
namespace rmx351\commons\Util;

use NumberFormatter;
use rmx351\commons\Money\Currency;

abstract class NumberUtils
{
    public static $DEFAULT_CURRENCY_OPTIONS = [
        'currency' => Currency::CURRENCY_CNY,
        'locale' => 'zh_CN',
        'precision' => 2,
        'trim_zero' => false,
    ];

    public static $DEFAULT_PERCENT_OPTIONS = [
        'locale' => 'zh_CN',
        'precision' => 1,
        'trim_zero' => false,
    ];

    public static $numberMap = [
        0   => '零',
        1   => '壹',
        2   => '贰',
        3   => '叁',
        4   => '肆',
        5   => '伍',
        6   => '陆',
        7   => '柒',
        8   => '捌',
        9   => '玖',
        '-' => '负',
        '.' => '',
    ];

    public static $unitMap = [
        '拾',
        '佰',
        '仟',
        '万',
        '亿',
    ];

    public static $moneyUnitMap = [
        ['圆', '元'],
        '角',
        '分',
        '厘',
        '毫',
    ];

    public static function formatCurrency($amount, array $options = [])
    {
        $options = array_merge(static::$DEFAULT_CURRENCY_OPTIONS, $options);

        if ($options['currency'] === Currency::CURRENCY_PNT) {
            return sprintf('%d Points', $amount);
        }

        $formatter = new NumberFormatter($options['locale'], NumberFormatter::CURRENCY);
        $formatter->setAttribute(NumberFormatter::FRACTION_DIGITS, $options['precision']);
        $myPattern = "¤#,##0.00;-¤#,##0.00";
        $formatter->setPattern($myPattern);

        $result = $formatter->formatCurrency($amount, $options['currency']);

        if (!empty($options['trim_zero'])) {
            $result = static::trimZero($result);
        }

        return $result;
    }

    public static function formatPercent($value, array $options = [])
    {
        $options = array_merge(static::$DEFAULT_PERCENT_OPTIONS, $options);

        $formatter = new NumberFormatter($options['locale'], NumberFormatter::PERCENT);
        $formatter->setAttribute(NumberFormatter::FRACTION_DIGITS, $options['precision']);

        $result = $formatter->format($value);

        if (!empty($options['trim_zero'])) {
            $result = static::trimZero(rtrim($result, '%')) . '%';
        }

        return $result;
    }

    /**
     * 讲金额转化成分
     * @param float $amount
     *
     * @return string
     */
    public static function toCent($amount)
    {
        $amount = trim($amount);
        $prefix = substr($amount, 0, 1);
        if ($prefix === '-') {
            $amount = substr($amount, 1);
        } else {
            $prefix = null;
        }
        $cleanString = preg_replace('/([^0-9\.,])/i', '', $amount);
        $onlyNumbersString = preg_replace('/([^0-9])/i', '', $amount);
        $separatorsCountToBeErased = strlen($cleanString) - strlen($onlyNumbersString) - 1;
        $stringWithCommaOrDot = preg_replace('/([,\.])/', '', $cleanString, $separatorsCountToBeErased);
        $amount = str_replace(',', '.', $stringWithCommaOrDot);
        $amount = $amount * 100;
        return $prefix . round($amount);
    }

    public static function trimZero($string)
    {
        return rtrim(preg_replace('/(\.\d*?)(0+)$/u', '$1', $string), '.');
    }

    /**
     * 金额转化成大写
     * @param float $number
     * @param array $options
     *
     * @return string
     */
    public static function toChinese($number, $options = [])
    {
        if (!static::verifyNumber($number)) {
            throw new \InvalidArgumentException(sprintf('%s is not a valied number', $number));
        }

        list($integer, $decimal) = explode('.', $number . '.');

        if ($integer < 0) {
            $pom = static::$numberMap['-'];
            $integer = abs($integer);
        } else {
            $pom = '';
        }

        return $pom . static::parseInteger($integer, $options) . static::parseDecimal($decimal, $options);
    }

    public static function verifyNumber($number)
    {
        return preg_match('/^-?\d+(\.\d+)?$/', $number) > 0;
    }

    private static function parseInteger($number, $options)
    {
        // 准备数据，分割为4个数字一组
        $length = \strlen($number);
        // 同 % 4
        $firstItems = $length & 3;
        $leftStr = substr($number, $firstItems);
        if ('' === $leftStr || false === $leftStr) {
            $split4 = [];
        } else {
            $split4 = str_split($leftStr, 4);
        }
        if ($firstItems > 0) {
            array_unshift($split4, substr($number, 0, $firstItems));
        }
        $split4Count = \count($split4);

        $unitIndex = ($length - 1) / 4 >> 0;

        if (0 === $unitIndex) {
            $unitIndex = -1;
        } else {
            $unitIndex += 2;
        }

        $result = '';
        foreach ($split4 as $i => $item) {
            $index = $unitIndex - $i;

            $length = \strlen($item);

            $itemResult = '';
            $has0 = false;
            for ($j = 0; $j < $length; ++$j) {
                if (0 == $item[$j]) {
                    $has0 = true;
                } else {
                    if ($has0) {
                        $itemResult .= static::$numberMap[0];
                        $has0 = false;
                    }
                    $itemResult .= static::$numberMap[$item[$j]];
                    if (isset(static::$unitMap[$length - $j - 2])) {
                        $itemResult .= static::$unitMap[$length - $j - 2];
                    }
                }
            }
            if ($has0) {
                $itemResult .= static::$numberMap[0];
            }
            if ('' === $itemResult) {
                if (isset(static::$unitMap[$index])) {
                    if ($index > 3) {
                        $result .= static::$unitMap[$index];
                    }
                } elseif ('0' != $item) {
                    $result .= isset(static::$unitMap[$index + 1]) ? static::$unitMap[$index + 1] : str_repeat(static::$unitMap[3], max($index - 3, 0));
                }
            } else {
                if ($i !== $split4Count - 1 && isset(static::$unitMap[$index])) {
                    $unit = static::$unitMap[$index];
                } else {
                    $unit = $index > 4 ? static::$unitMap[3] : '';
                }
                $result .= $itemResult . $unit;
            }
        }
        if ('' !== $result) {
            $result .= static::$moneyUnitMap[0][0];
        }

        return $result;
    }

    private static function parseDecimal($number, $options)
    {
        if ('' === $number) {
            return '';
        }
        $result = '';
        $length = \strlen($number);
        for ($i = 0; $i < $length; ++$i) {
            if (0 == $number[$i]) {
                $result .= static::$numberMap[$number[$i]];
            } else {
                $result .= static::$numberMap[$number[$i]] . (isset(static::$moneyUnitMap[$i + 1]) ? static::$moneyUnitMap[$i + 1] : '');
            }
        }
        $ltrimResult = Utils::mbLtrim($result, static::$numberMap[0]);

        return '' === $ltrimResult || $ltrimResult === $result ? $ltrimResult : (static::$numberMap[0] . $ltrimResult);
    }

    /**
     * 过滤金额
     * @param string $amount
     *
     * @return string|null
     */
    public static function filterAmount($amount)
    {
        $pattern = '/(\d+[\.\d+]?)/is';
        if (!preg_match_all($pattern, $amount, $matches)) {
            return null;
        }
        return implode('', $matches[0]);
    }
}
