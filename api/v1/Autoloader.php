<?php

class Autoloader
{
	protected $namespaceMap = array();

	public function addNamespace($namespace, $rootDir)
	{
		if (is_dir($rootDir)) {
			$this->namespaceMap[$namespace] = $rootDir;
			return true;
		} else {
			throw new Exception("Directory $rootDir doesn't exist!");
		}
	}

	public function register()
	{
		spl_autoload_register(array($this, 'autoload'));
	}

	public function autoload($className)
	{
		$pathParts = explode('\\', $className);

		if ($pathParts) {
			$namespace = array_shift($pathParts);

        	if (!empty($this->namespaceMap[$namespace])) {
        		$filePath = $this->namespaceMap[$namespace] . '/' . implode('/', $pathParts) . '.php';
        		require_once $filePath;

        		return true;
        	} else {
        		throw new Exception("Can't find namespace $namespace");
        	}
		}
	}
}