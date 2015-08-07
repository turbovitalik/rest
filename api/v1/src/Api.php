<?php

namespace Rest;

class Api
{
	protected $method;

	protected $param;

	protected $data;

	private $codes = array(
		'200' => 'OK',
		'201' => 'Created',
		'204' => 'No Content',
		'400' => 'Bad Request',
		'404' => 'Not Found',
		'500' => 'Internal Server Error'
	);

	public function handle()
	{	
		$this->method = $this->getMethod();
		if ($this->method == 'POST' || $this->method == 'PUT') {
			$this->data = $this->getData();
		}

        $parts = explode('/', $_SERVER['REQUEST_URI']);
		$controllerName = strtolower($parts[1]);

        if (isset($parts[2])) {
        	$this->param = $parts[2];
        }

		$controllerClass = ucfirst($controllerName) . 'Controller';

		if (file_exists(__DIR__ . '/controllers/' . $controllerClass . '.php')) {
			$controllerClass = '\\Rest\\controllers\\' . $controllerClass;
			$controller = new $controllerClass; 

			try {

				switch ($this->method) {
					case 'GET':
					    $result = $controller->view($this->param);
					    $this->sendResponseData($result);
					    break;
			
					case 'POST':
					    if (!$this->data) {
					    	throw new ApiException(400, "Data for creating isn't set!");
					    }
					    if ($this->param) {
					    	throw new ApiException(400);
					    }
					    $result = $controller->create($this->data);
					    $this->setStatus(201);
					    $this->sendResponseData(array('success' => 1));
					    break;
					
					case 'PUT':
					    if (!$this->data || !$this->param) {
					    	throw new ApiException(400, "Data for updating isn't set!");
					    }
					    $result = $controller->update($this->param, $this->data);
					    $this->setStatus(200);
					    $this->sendResponseData(array('success' => 1));
					    break; 
				}
			} catch (ApiException $e) {
				$this->handleError($e->getCode(), $e->getMessage());
			}        

		} else {
			$this->setStatus(404);
		}
	}

	public function handleError($code, $errorMessage = null)
	{
		$message = $this->codes[$code] . ($errorMessage ? '. ' . $errorMessage : '');

		$this->setStatus($code);
		$this->sendResponseData(array('error' => array('code' => $code, 'message' => $message)));
	}

	public function getControllerName()
	{
		$parts = explode('/', $_SERVER['REQUEST_URI']);
        $controller = strtolower($parts[1]);
		
		return $controller;
	}

	public function getData()
	{
		$data = file_get_contents('php://input');
		$data = json_decode($data, true);

		return $data;
	}

	public function getMethod()
	{
		$method = $_SERVER['REQUEST_METHOD'];
		if ($method == 'POST' && array_key_exists('HTTP_X_HTTP_METHOD', $_SERVER)) {
			switch ($_SERVER['HTTP_X_HTTP_METHOD']) {
				case 'DELETE':
				    $method = 'DELETE';
				    break;
				case 'PUT':
				    $method = 'PUT';
				    break;
				default: 
				    throw new Exception("Unexpected header!");
			}
		}

		return $method;
	}

	public function sendResponseData($data)
	{
		header('Content-Type: application/json');

		echo json_encode($data);
	}

	public function setStatus($code)
	{
		$protocol = 'HTTP/1.1';
		$code .= ' ' . $this->codes[strval($code)];
		header("$protocol $code");
	}
}