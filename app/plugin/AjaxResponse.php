<?php
namespace App\Plugin;


trait AjaxResponse
{

    protected $msg;

    public function jsonResponse($success, $data=null) {

        $this->app->response->headers->set('Content-Type', 'application/json');


        $respnse = [
            'success' => $success,
            'message' => $this->msg,
            'data' => $data,
        ];

        $this->app->response->setBody(json_encode($respnse));
    }
}