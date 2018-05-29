<?php
/**
 * author     : forecho <caizhenghai@gmail.com>
 * createTime : 2018-5-29 19:02:48
 * description:
 */

/**
 * Setup application environment
 */
$dotENV = new \Dotenv\Dotenv(dirname(__DIR__));
$dotENV->load();

defined('YII_DEBUG') or define('YII_DEBUG', getenv('YII_DEBUG') === 'true');
defined('YII_ENV') or define('YII_ENV', getenv('YII_ENV') ?: 'prod');