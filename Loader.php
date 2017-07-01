<?php

/**
 * Cache file
 * Cache lasttime
 * Date: 2017.7.1
 */

require(__DIR__ . '/PHPCache.php');

$conf = 'cache.json';

//set()方法 参数：key, value, time  true成功，false失败
PHPCache::Loader($conf)->set('page', 2, 20);					

//get()方法，参数：key  true成功，false失败
PHPCache::Loader($conf)->get('page');

//delete()方法，参数：key  true成功，false失败
PHPCache::Loader($conf)->delete('page');

//clear()方法， 请空缓存
$result = PHPCache::Loader($conf)->clear();

//日志文件 Cache.log
