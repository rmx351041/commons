<?php
namespace rmx351\commons\Util;

abstract class TimeUtils
{
    /**
     * 获得两两个时间的间隔
     * @param string $startedAt
     * @param string $endedAt
     * @param string[] $formatArray
     *
     * @return string
     */
    public static function formatDuration($startedAt, $endedAt, $formatArray = ['年', '月', '日', '时', '分', '秒'])
    {
        $startedAt = strtotime($startedAt);
        $endedAt = strtotime($endedAt);
        if (empty($startedAt) || empty($endedAt)) {
            return '-';
        }
        $remainderSeconds = abs($startedAt - $endedAt);
        //年
        $years = 0;
        if ($remainderSeconds - 31536000 > 0) {
            $years = floor($remainderSeconds / (31536000));
        }
        //月
        $months = 0;
        if ($remainderSeconds - $years * 31536000 - 2592000 > 0) {
            $months = floor(($remainderSeconds - $years * 31536000) / (2592000));
        }
        //日
        $days = 0;
        if ($remainderSeconds - $years * 31536000 - $months * 2592000 - 86400 > 0) {
            $days = floor(($remainderSeconds - $years * 31536000 - $months * 2592000) / (86400));
        }
        //时
        $hours = 0;
        if ($remainderSeconds - $years * 31536000 - $months * 2592000 - $days * 86400 - 3600 > 0) {
            $hours = floor(($remainderSeconds - $years * 31536000 - $months * 2592000 - $days * 86400) / 3600);
        }
        //分
        $minutes = 0;
        if ($remainderSeconds - $years * 31536000 - $months * 2592000 - $days * 86400 - $hours * 3600 - 60 > 0) {
            $minutes = floor(($remainderSeconds - $years * 31536000 - $months * 2592000 - $days * 86400 - $hours * 3600) / 60);
        }
        //秒
        $seconds = $remainderSeconds - $years * 31536000 - $months * 2592000 - $days * 86400 - $hours * 3600 - $minutes * 60;
        if ($years > 0) {
            $return = $years . $formatArray[0] . $months . $formatArray[1] . $days . $formatArray[2] . $hours . $formatArray[3] . $minutes . $formatArray[4] . $seconds . $formatArray[5];
        } else {
            if ($months > 0) {
                $return = $months . $formatArray[1] . $days . $formatArray[2] . $hours . $formatArray[3] . $minutes . $formatArray[4] . $seconds . $formatArray[5];
            } else {
                if ($days > 0) {
                    $return = $days . $formatArray[2] . $hours . $formatArray[3] . $minutes . $formatArray[4] . $seconds . $formatArray[5];
                } else {
                    if ($hours > 0) {
                        $return = $hours . $formatArray[3] . $minutes . $formatArray[4] . $seconds . $formatArray[5];
                    } else {
                        if ($minutes > 0) {
                            $return = $minutes . $formatArray[4] . $seconds . $formatArray[5];
                        } else {
                            $return = $seconds . $formatArray[5];
                        }
                    }
                }
            }
        }
        return $return;
    }

    /**
     * 获得两个时间见所有日期
     * @param string $startDate
     * @param string $endDate
     * @param string $group day 按天|month按月
     *
     * @return array
     */
    public static function getBetweenTimeArr($startDate, $endDate, $group = 'day')
    {
        $dtStart = strtotime($startDate);
        $dtEnd = strtotime($endDate);
        $getBetweenTimeArr = [];
        while ($dtStart <= $dtEnd) {
            if ($group === 'month') {
                $getBetweenTimeArr[] = date("Y-m", $dtStart);
                $dtStart = strtotime("+1 month", $dtStart);
            } else {
                $getBetweenTimeArr[] = date("Y-m-d", $dtStart);
                $dtStart = strtotime("+1 day", $dtStart);
            }
        }
        return $getBetweenTimeArr;
    }
}
