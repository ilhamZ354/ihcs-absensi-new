<?php

namespace  App\Services\IHCS\Foundation;

class Config 
{
	
	protected $paths = array();

	public function __construct($filename)
	{
		$this->load($filename);
	}

	private function load($filename)
	{
		$dir = $this->getDirectory();

		$path =  require $dir . "config" . DIRECTORY_SEPARATOR . "{$filename}.php";

		$this->paths = $path;
	}

	public function getArray()
	{
		return $this->paths;
	}

	public function get($alias)
	{
		return isset($this->paths[$alias]) ? $this->paths[$alias] : null;
	}
	
	public function set($alias, $path)
	{
		$this->paths[$alias] = $path;
	}

	public function flush($alias = null)
	{
		if(!is_null($alias) && $this->get($alias) != false)
		{
			unset($this->paths[$alias]);
			return true;
		}
		
		$this->paths = array();
		return true;
	}

	private function getDirectory()
	{
		$directory = explode(DIRECTORY_SEPARATOR, __DIR__);
		
		unset($directory[count($directory) - 1]);

		return implode(DIRECTORY_SEPARATOR, $directory) . DIRECTORY_SEPARATOR;
	}
}