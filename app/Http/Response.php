<?php


namespace App\Http;

use Illuminate\Http\Resources\Json\JsonResource;
use  \Illuminate\Http\Response as HttpResponse;

class Response
{
    public function errorNotFound($message = 'Not Found')
    {
        $this->fail($message, HttpResponse::HTTP_NOT_FOUND);
    }

    /**
     * @param  string  $message
     * @param  int  $code
     * @param  null  $data
     * @param  array  $header
     * @param  int  $options
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     */
    public function fail(string $message = '', int $code = HttpResponse::HTTP_INTERNAL_SERVER_ERROR, $data = null, array $header = [], int $options = 0)
    {
        $status = ($code >= 400 && $code <= 499) ? 'error' : 'fail';
        $message = (!$message && isset(HttpResponse::$statusTexts[$code])) ? HttpResponse::$statusTexts[$code] : 'Service error';

        response()->json([
            'status' => $status,
            'code' => $code,
            'message' => $message,// 错误描述
            'data' => (object) $data,// 错误详情
        ], $code, $header, $options)->throwResponse();
    }

    public function errorBadRequest($message = 'Bad Request')
    {
        $this->fail($message, HttpResponse::HTTP_BAD_REQUEST);
    }

    public function errorForbidden($message = 'Forbidden')
    {
        $this->fail($message, HttpResponse::HTTP_FORBIDDEN);
    }

    public function errorInternal($message = 'Internal Error')
    {
        $this->fail($message, HttpResponse::HTTP_INTERNAL_SERVER_ERROR);
    }

    public function errorUnauthorized($message = 'Unauthorized')
    {
        $this->fail($message, HttpResponse::HTTP_UNAUTHORIZED);
    }

    public function errorMethodNotAllowed($message = 'Method Not Allowed')
    {
        $this->fail($message, HttpResponse::HTTP_METHOD_NOT_ALLOWED);
    }

    public function accepted($message = 'Accepted')
    {
        return $this->success(null, $message, HttpResponse::HTTP_ACCEPTED);
    }

    /**
     * @param  JsonResource|array|null  $data
     * @param  string  $message
     * @param  int  $code
     * @param  array  $headers
     * @param  int  $option
     * @return \Illuminate\Http\JsonResponse|JsonResource
     */
    public function success($data, string $message = '', $code = HttpResponse::HTTP_OK, array $headers = [], $option = 0)
    {
        $message = (!$message && isset(HttpResponse::$statusTexts[$code])) ? HttpResponse::$statusTexts[$code] : 'OK';
        $additionalData = [
            'status' => 'success',
            'code' => $code,
            'message' => $message
        ];

        if ($data instanceof JsonResource) {
            return $data->additional($additionalData);
        }

        return response()->json(array_merge($additionalData, ['data' => $data ?: (object) $data]), $code, $headers, $option);
    }

    /**
     * @param  JsonResource|array|null  $data
     * @param  string  $message
     * @param  string  $location
     * @return \Illuminate\Http\JsonResponse|JsonResource
     */
    public function created($data = null, $message = 'Created', string $location = '')
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
