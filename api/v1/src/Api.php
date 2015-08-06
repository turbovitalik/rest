<?php

namespace Rest;

use \Rest\controllers\AddressController;

class Api
{
	protected $root = '/api/v1/';

	protected $method;

	protected $resource;

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
		$this->resource = str_replace($this->root, '', $_SERVER['REQUEST_URI']);
		$this->resource = str_replace('?', '', $this->resource);     

		$this->method = $this->getMethod();

		if ($this->method == 'POST' || $this->method == 'PUT') {
			$this->data = $this->getData();
		}

        $controller = new AddressController();

        try {

            if (!preg_match('|^/addresses\/?(\d*)?\/?$|', $this->resource, $match)) {
        	    throw new ApiException(400);
            }

            $this->param = $match[1];

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
        } catch (Exception $e) {
            echo "{$e->getMessage()}";
        } catch (ApiException $e) {
        	$this->handleError($e->getCode(), $e->getMessage());
        }    
	}

	public function handleError($code, $errorMessage = null)
	{
		$message = $this->codes[$code] . ($errorMessage ? '. ' . $errorMessage : '');

		$this->setStatus($code);
		$this->sendResponseData(array('error' => array('code' => $code, 'message' => $message)));
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