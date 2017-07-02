<?php

/**
 * Cache file
 * Cache lasttime
 * Date: 2017.7.1
 */

require(__DIR__ . '/PHPCache.php');

//set()方法 参数：key, value, time  true成功，false失败
PHPCache::Loader()->set('page', 2, 20);					

//get()方法，参数：key  true成功，false失败
PHPCache::Loader()->get('page');

//delete()方法，参数：key  true成功，false失败
PHPCache::Loader()->delete('page');

//flush()方法， 请空缓存
PHPCache::Loader()->flush();

//日志文件 Cache.log
