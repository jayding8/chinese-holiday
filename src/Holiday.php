<?php
/**
 * Created by PhpStorm.
 * User: jayding
 * Date: 2020/12/4
 * Time: 20:39
 */

namespace Holiday;

use GuzzleHttp\Client;

define('RESOURCE_DIR', realpath(dirname(__FILE__)) . '/../');

class Holiday
{
    protected $year, $month, $day, $urls;

    public function __construct()
    {
        $this->urls = [
            'year'  => 'http://timor.tech/api/holiday/year/{{year}}?type=Y&week=Y',
            'month' => 'http://timor.tech/api/holiday/year/{{month}}?type=Y&week=Y',
            'day'   => 'http://timor.tech/api/holiday/info/{{day}}',
        ];
    }

    /**
     * 校验某一天是否是 法定节假日
     *
     * @param string 2020-12-29
     */
    public function check($date = '')
    {
        return $this->getHolidayDay($date);
    }

    /**
     * 获取指定年份的节日
     *
     * @param int 2020
     */
    public function getHolidayYear($year = '')
    {
        $this->setYear($year);
        $data = $this->getData('year');
        return $data;
    }

    /**
     * 获取指定 年月的节日
     *
     * @param string 2020-08
     */
    public function getHolidayMonth($month = '')
    {
        $this->setMonth($month);
        $data = $this->getData('month');
        return $data;
    }

    /**
     * 获取指定 日期的节日
     *
     * @param string 2020-08-10
     */
    public function getHolidayDay($day = '')
    {
        $this->setDay($day);
        $data = $this->getData('day');
        return $data;
    }

    /**
     * 本地获取,没有就 回调源站
     */
    protected function getData($type)
    {
        $filename = RESOURCE_DIR . 'resources/' . $type . '/' . $this->$type;
        if (file_exists($filename)) {
            $data = file_get_contents($filename);
            $data = json_decode($data);
        } else {
            $data = $this->getSourceData($type);
        }
        return $data;
    }

    protected function getSourceData($type)
    {
        $url    = $this->urls[$type];
        $url    = str_replace('{{' . $type . '}}', $this->$type, $url);
        $client = new Client();
        $result = $client->request('GET', $url);
        $result = json_decode($result->getBody(), 1);
        $return = [];
        if ($result['code'] == 0) {
            file_put_contents(RESOURCE_DIR . 'resources/' . $type . '/' . $this->$type, json_encode($result, JSON_UNESCAPED_UNICODE));
            $return = $result;
        }
        return $return;
    }

    protected function setYear($year = '')
    {
        $this->year = $year ? date('Y', strtotime($year)) : date('Y', time());
    }

    protected function setMonth($month = '')
    {
        $this->month = $month ? date('Y-m', strtotime($month)) : date('Y-m', time());
    }

    protected function setDay($day = '')
    {
        $this->day = $day ? date('Y-m-d', strtotime($day)) : date('Y-m-d', time());
    }
}