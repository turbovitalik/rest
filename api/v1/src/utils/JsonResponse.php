<?php

namespace Rest\Utils;

class JsonResponse
{
    const HTTP_OK = 200;
    const HTTP_CREATED = 201;
    const HTTP_BAD_REQUEST = 400;
    const HTTP_NOT_FOUND = 404;
    const HTTP_SERVER_ERROR = 500;

    const STATUS_OK = 'OK';
    const STATUS_CREATED = 'Created';
    const STATUS_BAD_REQUEST = 'Bad Request';
    const STATUS_NOT_FOUND = 'Not Found';
    const STATUS_SERVER_ERROR = 'Internal Server Error';

    /**
     * @var null|string
     */
    private $content;

    /**
     * @var int
     */
    private $statusCode;

    /**
     * @var string
     */
    private $statusText = '';

    /**
     * @var array
     */
    private $statusTextMap = [
        self::HTTP_OK => self::STATUS_OK,
        self::HTTP_CREATED => self::STATUS_CREATED,
        self::HTTP_BAD_REQUEST => self::STATUS_BAD_REQUEST,
        self::HTTP_NOT_FOUND => self::STATUS_NOT_FOUND,
        self::HTTP_SERVER_ERROR => self::STATUS_SERVER_ERROR,
    ];

    /**
     * Response constructor.
     * @param $content
     */
    public function __construct($content = null)
    {
        $this->content = $content;
    }

    /**
     * @param $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * @param $statusCode
     */
    public function setStatus($statusCode)
    {
        $this->statusCode = $statusCode;

        $this->resolveStatusText();
    }

    private function resolveStatusText()
    {
        if (null !== $this->statusCode) {
            $this->statusText = array_key_exists($this->statusCode, $this->statusTextMap) ?
                $this->statusTextMap[$this->statusCode] : '';
        }
    }

    public function send()
    {
        $protocol = 'HTTP/1.1';
        header('Content-Type: application/json');
        header($protocol . ' ' . $this->statusCode . ' ' . $this->statusText);
        echo $this->content;

        fastcgi_finish_request();
    }
}
