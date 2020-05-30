<?php


namespace App\Http;

use App\Exceptions\HttpException;
use Illuminate\Http\Resources\Json\JsonResource;
use Symfony\Component\HttpFoundation\Response as FoundationResponse;

class Response
{
    public function errorNotFound($message = 'Not Found')
    {
        return $this->fail(null, $message, FoundationResponse::HTTP_NOT_FOUND);
    }

    public function fail($data = null, $message = 'Service error', $code = FoundationResponse::HTTP_INTERNAL_SERVER_ERROR)
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
        return $this->fail(null, $message, FoundationResponse::HTTP_BAD_REQUEST);
    }

    public function errorForbidden($message = 'Forbidden')
    {
        return $this->fail(null, $message, FoundationResponse::HTTP_FORBIDDEN);
    }

    public function errorInternal($message = 'Internal Error')
    {
        return $this->fail(null, $message, FoundationResponse::HTTP_INTERNAL_SERVER_ERROR);
    }


    public function errorUnauthorized($message = 'Unauthorized')
    {
        return $this->fail(null, $message, FoundationResponse::HTTP_UNAUTHORIZED);
    }

    public function errorMethodNotAllowed($message = 'Method Not Allowed')
    {
        return $this->error($message, FoundationResponse::HTTP_METHOD_NOT_ALLOWED);
    }

    public function accepted($message = 'Accepted')
    {
        return $this->success(null, $message, FoundationResponse::HTTP_ACCEPTED);
    }

    public function success($data, $message, $code = FoundationResponse::HTTP_OK)
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

    public function created($data, $message = 'Created')
    {
        return $this->success($data, $message, FoundationResponse::HTTP_CREATED);
    }

    public function noContent($message = 'No content')
    {
        return $this->success(null, $message, FoundationResponse::HTTP_NO_CONTENT);
    }
}
