<?php

namespace le7\Core\Helpers;

use DateTime;
use Exception;

class DateTimeHelper
{

    private array $errors = array();
    private string $default_format = 'Y-m-d H:i:s';

    public function __construct()
    {

    }

    /**
     * @param string $date
     * @param bool $error_if_empty
     * @return object
     */
    public function dateAsObject(string $date, bool $error_if_empty = false) : object
    {

        if (empty($date)) {
            $date = date($this->default_format);
            if ($error_if_empty) {
                $this->errors[] = _('Empty date in processDate');
            }
        }

        $date_exploded = explode(' ', $date);

        $sep_date = $date_exploded[0];
        $sep_time = $date_exploded[1];

        $sep_date_prepared = explode('-', $sep_date);
        $sep_time_prepared = explode(':', $sep_time);

        $result = array(
            'year' => $sep_date_prepared[0],
            'month' => $sep_date_prepared[1],
            'day' => $sep_date_prepared[2],
            'hour' => $sep_time_prepared[0],
            'minute' => $sep_time_prepared[1],
            'second' => $sep_time_prepared[2],
            'date' => $sep_date_prepared[0] . '-' . $sep_date_prepared[1] . '-' . $sep_date_prepared[2],
            'time' => $sep_time_prepared[0] . ':' . $sep_time_prepared[1] . ':' . $sep_time_prepared[2]
        );

        return (object) $result;
    }

    /**
     * @param $date
     * @return bool
     */
    function validate(string $date) : bool
    {
        $d = DateTime::createFromFormat($this->default_format, $date);
        return $d && $d->format($this->default_format) == $date;
    }


    /**
     * @param $strDate
     * @return float|int
     * @throws Exception
     */
    function weekOfMonth($strDate) {
        $dateArray = explode("-", $strDate);
        $date = new DateTime();
        $date->setDate($dateArray[0], $dateArray[1], $dateArray[2]);
        return floor((date_format($date, 'j') - 1) / 7) + 1;
    }

    /**
     * @param string $date
     * @return string
     */
    function dayOfWeek(string $date = 'today') : string {
        if ($date === 'today') {
            $date = date('Y-m-d');
        }
        return date("N", strtotime($date));
    }

    /**
     * @return string
     */
    public function getDefaultFormat() {
        return $this->default_format;
    }

    /**
     * @param $defaultFormat
     * @return DateTimeHelper
     */
    public function setDefaultFormat($defaultFormat) {
        $this->default_format = $defaultFormat;
        return $this;
    }

    /**
     * @return array
     */
    public function getErrors() {
        return $this->errors;
    }

    /**
     * @param $date
     * @param string $days
     * @return false|string
     */
    public function dateMinusNDays($date, $days='0') {
        return date('Y-m-d', strtotime('-'.$days.' day', strtotime($date)));
    }

    /**
     * @param $date
     * @param string $days
     * @return false|string
     */
    public function datePlusNDays($date, $days='0') {
        return date('Y-m-d', strtotime('+'.$days.' day', strtotime($date)));
    }

    /**
     * create time range by CodexWorld
     *
     * @param mixed $start start time, e.g., 7:30am or 7:30
     * @param mixed $end end time, e.g., 8:30pm or 20:30
     * @param string $interval time intervals, 1 hour, 1 mins, 1 secs, etc.
     * @param string $format time format, e.g., H:i:s
     * @return array
     */
    function getTimeRange(string $start = '00:00', string $end = '23:55', string $interval = '30 mins', string $format = 'H:i:s') : array {
        $startTime = strtotime($start);
        $endTime   = strtotime($end);
        $returnTimeFormat = $format;

        $current   = time();
        $addTime   = strtotime('+'.$interval, $current);
        $diff      = $addTime - $current;

        $times = array();
        while ($startTime < $endTime) {
            $times[] = date($returnTimeFormat, $startTime);
            $startTime += $diff;
        }
        $times[] = date($returnTimeFormat, $startTime);
        return $times;
    }

    /**
     * @param $time1
     * @param $time2
     * @param string $format
     * @return string
     */
    public function timeDiff($time1, $time2, $format = '%H:%i:%s') {
        $time1c = null;
        $time2c = null;
        try {
            $time1c = new DateTime($time1);
        } catch (Exception $e) {
            $this->errors[] = $e;
        }
        try {
            $time2c = new DateTime($time2);
        } catch (Exception $e) {
            $this->errors[] = $e;
        }
        $interval = $time1c->diff($time2c);
        //echo $interval->format($format) . "\n";
        return $interval->format($format);
    }

    /**
     * @param $time
     * @return float|int
     */
    public function timeToMinutes($time){
        $time = explode(':', $time);
        return ($time[0]*60) + ($time[1]) + ($time[2]/60);
    }

    public function minutesToHours(int $time, string $format = '%02d:%02d') : string {
        if ($time < 1) {
            return '';
        }
        $hours = floor($time / 60);
        $minutes = ($time % 60);
        return sprintf($format, $hours, $minutes);
    }

    /**
     * @param $monthNum
     * @return string
     */
    public function getMonthNameByMonth(string $monthNum) : string {
        $dateObj   = DateTime::createFromFormat('!m', $monthNum);
        $monthName = $dateObj->format('F'); // March
        return $monthName;
    }

    /**
     * @param bool $fullName
     * @return string
     */
    public function getLastMonth($fullName = false) : string {
        if ($fullName) {
            $q = 'F';
        } else {
            $q = 'm';
        }
        return date($q, strtotime("first day of previous month"));
    }

    /**
     * @param int $month
     * @param int $year
     * @return array
     */
    public function getDatesInMonth(int $month, int $year) : array {
        $start_date = "01-".$month."-".$year;
        $start_time = strtotime($start_date);

        $end_time = strtotime("+1 month", $start_time);

        $list = array();
        for($i=$start_time; $i<$end_time; $i+=86400)
        {
            $list[] = date('Y-m-d', $i);
        }
        return $list;
    }

}
