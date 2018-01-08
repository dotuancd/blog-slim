<?php

namespace App\Support;

 class ApiErrorResponder
 {
     protected $responder;

     public function __construct($response)
     {
         $this->responder = $response;
     }

     public function unauthorized()
     {
         return $this->error(401, 'Unauthorized');
     }

     public function notFound()
     {
         return $this->error(404, 'Not Found');
     }

     public function forbidden($message = 'Forbidden')
     {
         return $this->error(403, $message);
     }

     public function error($code = 0, $message = 'Oops, something wrong. Please try later.')
     {
         $error =  [
             'error' => true,
             'code' => $code,
             'message' => $message
         ];
         return $this->responder->withJson($error)->withStatus($code);
     }

     public static function make($responder)
     {
         return new static($responder);
     }
 }