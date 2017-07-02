<?php

/* Cache data
 * Auther:devkeep
 * Date:2017.7.1
 */

class PHPCache
{
	private static $single;

	private $filename;
	
	private function __construct($filename = 'cache.json')
	{
		$this->filename = $filename;
	}

	//单例设计
	static function Loader()
	{
		if(self::$single)
		{
			return self::$single;
		}
		else
		{
			self::$single = new self();

			return self::$single;
		}
	}

	//set key
	public function set($key = false, $value = false, $lasttime = 60)
	{
		$data = $this->LoadVery($key);

		if( $data !== false && $value !== false )
		{
			$data[$key] = array
			(
				'data' 		=> $value,						//数据
				'lasttime'	=> time() + (int)$lasttime,		//缓存时间
			);

			//缓存加锁写入
			return $this->write($data);	
		}
		else
		{
			return false;
		}
	}

	//get key
	public function get($key = false)
	{
		$data = $this->LoadVery($key);

		if($data !== false)
		{
			if(array_key_exists($key, $data))
			{
				if( time() <= $data[$key]['lasttime'] )
				{
					return $data[$key]['data'];
				}
				else //缓存加锁写入
				{
					unset($data[$key]);

					if( file_put_contents($this->filename, json_encode($data), LOCK_EX) )
					{
						return false;
					}
					else //错误日志
					{
						$this->CacheLog($key);
					}					
				}
			}
			else
			{
				return false;
			}
		}
		else
		{
			return false;
		}
	}

	//delete key
	public function delete($key = false)
	{
		$data = $this->LoadVery($key);

		if( $data !== false )
		{
			if(array_key_exists($key, $data))
			{
				unset($data[$key]);
				return $this->write($data);
			}
			else
			{
				return false;
			}
		}
		else
		{
			return false;
		}
	}

	//flush
	public function flush()
	{
		//缓存清空
		if( file_put_contents($this->filename, '[]', LOCK_EX) )
		{
			return true;
		}
		else //错误日志
		{
			$this->CacheLog($key);
			return false;
		}
	}

	//文件加锁写入
	private function write($data)
	{
		if( file_put_contents($this->filename, json_encode($data), LOCK_EX) )
		{
			return true;
		}
		else //错误日志
		{
			$this->CacheLog($key);
			return false;
		}	
	}

	//缓存验证
	private function LoadVery($key)
	{
		$key = str_replace(' ', '', $key);
		
		if($key && is_string($key))
		{
			if(!file_exists($this->filename))
			{
				file_put_contents( $this->filename , '[]');
			}

			$json = file_get_contents( $this->filename );

			$data = json_decode($json, true);

			return $data;
		}
		else
		{
			return false;
		}	
	}

	//缓存日志
	private function CacheLog($key)
	{
		file_put_contents('Cache.log', $key . ' Write failed', FILE_APPEND|LOCK_EX);
	}
}
