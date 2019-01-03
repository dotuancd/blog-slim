<?php

namespace App\Http\Response;

use Slim\Http\Response;

/**
 * Class ErrorFormatter
 * @mixin Response
 */
class ErrorBuilder
{
    /**
     * @var Response
     */
    private $response;

    public function __construct(Response $response)
    {
        $this->response = $response;
    }

    public function unauthorized($message = 'Unauthorized')
    {
        return $this->format(401, $message);
    }

    public function notFound($message = 'Not found')
    {
        return $this->format(404, $message);
    }

    public function forbidden($message = 'Forbidden')
    {
        return $this->format(403, $message);
    }

    /**
     * @param string $message
     * @param array $fields
     *
     * @return Response
     */
    public function validation($fields = [], $message = 'Oops, Needs to check the data in request')
    {
        return $this->format(422, $message, ['fields' => $fields]);
    }

    public function format($code = 0, $message = 'Oops, something wrong. Please try later.', $appends = [])
    {
        $error = array_merge(
            [
                'error' => true,
                'code' => $code,
                'message' => $message
            ],
            $appends
        );

        return $this->response->withJson($error)->withStatus($code);
    }
    /**
     * Dynamic passthru to response
     *
     * @param $name
     * @param $arguments
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        return call_user_func_array([$this->response, $name], $arguments);
    }

    /**
     * Allows chain methods calling
     *
     * @param Response $response
     * @return ErrorBuilder
     */
    public static function make(Response $response)
    {
        return new static($response);
    }
}