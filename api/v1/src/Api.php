<?php

namespace Rest;

use Pimple\Container;
use Rest\Utils\JsonResponse;
use Rest\Utils\Request;

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

	public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function run()
    {
        try {
            $request = $this->container['app.request'];
            $response = $this->handle($request);
        } catch (\Exception $e) {
            $response = new JsonResponse();
            $response->setContent($e->getMessage());
            $response->setStatus(JsonResponse::HTTP_BAD_REQUEST);
        }

        $response->send();
    }

    /**
     * @param Request $request
     */
	public function handle(Request $request)
	{
        $router = $this->container['app.router'];

        $response = $router->handleRequest($request);

        return $response;
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