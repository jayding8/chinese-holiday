<?php
/**
 * Created by PhpStorm.
 * User: jayding
 * Date: 2020/12/4
 * Time: 20:49
 */

require_once __DIR__ . '/vendor/autoload.php';

use Holiday\Holiday;

$holiday = new Holiday();
echo '<pre>';
var_dump($holiday->check('2020-10-01'));