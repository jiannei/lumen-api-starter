<?php

/*
 * This file is part of the Jiannei/lumen-api-starter.
 *
 * (c) Jiannei <longjian.huang@foxmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Http;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use  Illuminate\Http\Response as HttpResponse;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Arr;

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
        response()->json(
            array_merge_recursive($this->withAdditional($message,$code), ['data' => (object)$data]),
            $code,
            $header,
            $options
        )->throwResponse();
    }

    /**
     * return custom additional data
     *
     * @param  string  $message
     * @param  int  $code
     * @return array
     */
    protected function withAdditional(string $message = '', $code = HttpResponse::HTTP_OK)
    {
        if ($code >= 400 && $code <= 499) {
            $status = 'error';
        } elseif ($code >= 500 && $code <= 599) {
            $status = 'fail';
        } else {
            $status = 'success';
        }

        $message = (!$message && isset(HttpResponse::$statusTexts[$code])) ? HttpResponse::$statusTexts[$code] : $message;

        return [
            'status' => $status,
            'code' => $code,
            'message' => $message,
        ];
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
        if (!$data instanceof JsonResource) {
            return response()->json(array_merge($this->withAdditional($message, $code), ['data' => $data ?: (object)$data]), $code, $headers, $option);
        }

        if ($data instanceof ResourceCollection && $data->resource instanceof Paginator) {
            return $this->formatPaginatedResourceResponse(...func_get_args());
        }

        return $this->formatResourceResponse(...func_get_args());
    }

    /**
     * Format paginated resource data
     *
     * @param  JsonResource  $resource
     * @param  string  $message
     * @param  int  $code
     * @param  array  $headers
     * @param  int  $option
     * @return \Illuminate\Support\HigherOrderTapProxy|mixed
     */
    protected function formatPaginatedResourceResponse($resource, string $message = '', $code = HttpResponse::HTTP_OK, array $headers = [], $option = 0)
    {
        $paginated = $resource->resource->toArray();

        $paginationInformation = [
            'links' => [
                'first' => $paginated['first_page_url'] ?? null,
                'last' => $paginated['last_page_url'] ?? null,
                'prev' => $paginated['prev_page_url'] ?? null,
                'next' => $paginated['next_page_url'] ?? null,
            ],
            'meta' => Arr::except(
                $paginated,
                [
                    'data',
                    'first_page_url',
                    'last_page_url',
                    'prev_page_url',
                    'next_page_url',
                ]
            ),
        ];

        $wrappedData = array_merge_recursive(
            [
                'data' => array_merge_recursive(['pagination' => $this->parseDataFrom($resource)], $paginationInformation),
            ],
            $this->withAdditional($message, $code)
        );

        return tap(
            response()->json($wrappedData, $code, $headers, $option),
            function ($response) use ($resource) {
                $response->original = $resource->resource->map(
                    function ($item) {
                        return is_array($item) ? Arr::get($item, 'resource') : $item->resource;
                    }
                );

                $resource->withResponse(request(), $response);
            }
        );
    }

    /**
     * Parse data from JsonResource
     *
     * @param  JsonResource  $data
     * @return array
     */
    protected function parseDataFrom(JsonResource $data)
    {
        return array_merge_recursive($data->resolve(request()), $data->with(request()), $data->additional);
    }

    /**
     * Format JsonResource Data
     *
     * @param  JsonResource  $resource
     * @param  string  $message
     * @param  int  $code
     * @param  array  $headers
     * @param  int  $option
     * @return \Illuminate\Support\HigherOrderTapProxy|mixed
     */
    protected function formatResourceResponse($resource, string $message = '', $code = HttpResponse::HTTP_OK, array $headers = [], $option = 0)
    {
        $wrappedData = array_merge_recursive(['data' => $this->parseDataFrom($resource)], $this->withAdditional($message, $code));

        return tap(
            response()->json($wrappedData, $code, $headers, $option),
            function ($response) use ($resource) {
                $response->original = $resource->resource;

                $resource->withResponse(request(), $response);
            }
        );
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
