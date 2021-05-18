<?php
namespace rmx351\commons\Util;

abstract class Utils
{
    public static function mbLtrim($string, $trim_chars = '\s')
    {
        return preg_replace('/^[' . $trim_chars . ']*(.*?)$/u', '\\1', $string);
    }

    public static function mbRtrim($string, $trim_chars = '\s')
    {
        return preg_replace('/^(.*?)[' . $trim_chars . ']*$/u', '\\1', $string);
    }

    /**
     * 取消驼峰形式，以指定字符串连接（默认 '_' 下划线）
     * @param string $camelCaps
     * @param string $separator
     *
     * @return string
     */
    public static function unCamelize($camelCaps, $separator = '_')
    {
        return strtolower(preg_replace('/([a-z])([A-Z])/', "$1" . $separator . "$2", $camelCaps));
    }

    /**
     * 字符串转化成驼峰模式
     * @param string $text
     * @param bool $isParameter 参数名首字母小写，类名首字母大写
     *
     * @return string
     */
    public static function camelize($text, $isParameter = true)
    {
        $text = strtr(ucwords(strtr($text, ['_' => ' ', '.' => '_ ', '\\' => '_ '])), [' ' => '']);
        if ($isParameter) {
            $text = lcfirst($text);
        }
        return $text;
    }
}
