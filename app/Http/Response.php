<?php


namespace App\Http;

use Illuminate\Http\Resources\Json\JsonResource;
use  \Illuminate\Http\Response as HttpResponse;

class Response
{
    public function errorNotFound($message = 'Not Found')
    {
        return $this->fail(null, $message, HttpResponse::HTTP_NOT_FOUND);
    }

    public function fail($data = null, $message = 'Service error', $code = HttpResponse::HTTP_INTERNAL_SERVER_ERROR)
    {
        $status = 'fail';
        if ($code >= 400 && $code <= 499) {
            $status = 'error';
        }

        return response([
            'status' => $status,
            'code' => $code,
            'message' => $message,
            'data' => (object) $data,
        ], $code);
    }

    public function errorBadRequest($message = 'Bad Request')
    {
        return $this->fail(null, $message, HttpResponse::HTTP_BAD_REQUEST);
    }

    public function errorForbidden($message = 'Forbidden')
    {
        return $this->fail(null, $message, HttpResponse::HTTP_FORBIDDEN);
    }

    public function errorInternal($message = 'Internal Error')
    {
        return $this->fail(null, $message, HttpResponse::HTTP_INTERNAL_SERVER_ERROR);
    }


    public function errorUnauthorized($message = 'Unauthorized')
    {
        return $this->fail(null, $message, HttpResponse::HTTP_UNAUTHORIZED);
    }

    public function errorMethodNotAllowed($message = 'Method Not Allowed')
    {
        return $this->fail($message, HttpResponse::HTTP_METHOD_NOT_ALLOWED);
    }

    public function accepted($message = 'Accepted')
    {
        return $this->success(null, $message, HttpResponse::HTTP_ACCEPTED);
    }

    public function success($data, $message = '', $code = HttpResponse::HTTP_OK)
    {
        if ($data instanceof JsonResource) {
            $data->additional([
                'status' => 'success',
                'code' => $code,
                'message' => $message
            ]);

            return $data;
        }

        return response($data, $code);
    }

    public function created($data, $message = 'Created', $location = null)
    {
        $response = $this->success($data, $message, HttpResponse::HTTP_CREATED);
        if ($location) {
            $response->header('Location', $location);
        }

        return $response;
    }

    public function noContent($message = 'No content')
    {
        return $this->success(null, $message, HttpResponse::HTTP_NO_CONTENT);
    }
}
