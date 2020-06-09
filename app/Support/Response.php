<?php

/*
 * This file is part of the Jiannei/lumen-api-starter.
 *
 * (c) Jiannei <longjian.huang@foxmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Support;

use App\Repositories\Constants\ResponseConstant;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use  Illuminate\Http\Response as HttpResponse;
use Illuminate\Pagination\AbstractPaginator;
use Illuminate\Support\Arr;

class Response
{
    public function errorNotFound(string $message = '')
    {
        $this->fail($message, HttpResponse::HTTP_NOT_FOUND);
    }

    /**
     * @param string $message
     * @param int    $code
     * @param null   $data
     * @param array  $header
     * @param int    $options
     *
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     */
    public function fail(string $message = '', int $code = HttpResponse::HTTP_INTERNAL_SERVER_ERROR, $data = null, array $header = [], int $options = 0)
    {
        response()->json(
            $this->formatData($data, $message, $code),
            $code,
            $header,
            $options
        )->throwResponse();
    }

    public function errorBadRequest(string $message = '')
    {
        $this->fail($message, HttpResponse::HTTP_BAD_REQUEST);
    }

    public function errorForbidden(string $message = '')
    {
        $this->fail($message, HttpResponse::HTTP_FORBIDDEN);
    }

    public function errorInternal(string $message = '')
    {
        $this->fail($message, HttpResponse::HTTP_INTERNAL_SERVER_ERROR);
    }

    public function errorUnauthorized(string $message = '')
    {
        $this->fail($message, HttpResponse::HTTP_UNAUTHORIZED);
    }

    public function errorMethodNotAllowed(string $message = '')
    {
        $this->fail($message, HttpResponse::HTTP_METHOD_NOT_ALLOWED);
    }

    public function accepted(string $message = '')
    {
        return $this->success(null, $message, HttpResponse::HTTP_ACCEPTED);
    }

    /**
     * @param JsonResource|array|null $data
     * @param string                  $message
     * @param int                     $code
     * @param array                   $headers
     * @param int                     $option
     *
     * @return \Illuminate\Http\JsonResponse|JsonResource
     */
    public function success($data = null, string $message = '', $code = HttpResponse::HTTP_OK, array $headers = [], $option = 0)
    {
        if (! $data instanceof JsonResource) {
            return $this->formatArrayResponse($data, $message, $code, $headers, $option);
        }

        if ($data instanceof ResourceCollection && ($data->resource instanceof AbstractPaginator)) {
            return $this->formatPaginatedResourceResponse(...func_get_args());
        }

        return $this->formatResourceResponse(...func_get_args());
    }

    /**
     * Format normal array data.
     *
     * @param array|null $data
     * @param string     $message
     * @param int        $code
     * @param array      $headers
     * @param int        $option
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function formatArrayResponse($data, string $message = '', $code = HttpResponse::HTTP_OK, array $headers = [], $option = 0)
    {
        return response()->json($this->formatData($data, $message, $code), $code, $headers, $option);
    }

    /**
     * @param JsonResource|array|null $data
     * @param $message
     * @param $code
     *
     * @return array
     */
    protected function formatData($data, $message, &$code)
    {
        $originalCode = $code;
        $code = (int) substr($code, 0, 3); // notice

        if ($code >= 400 && $code <= 499) {// client error
            $status = 'error';
        } elseif ($code >= 500 && $code <= 599) {// service error
            $status = 'fail';
        } else {
            $status = 'success';
        }

        return [
            'status' => $status,
            'code' => $originalCode,
            'message' => $message ?: ResponseConstant::statusTexts($originalCode),
            'data' => $data ?: (object) $data,
        ];
    }

    /**
     * Format paginated resource data.
     *
     * @param JsonResource $resource
     * @param string       $message
     * @param int          $code
     * @param array        $headers
     * @param int          $option
     *
     * @return \Illuminate\Support\HigherOrderTapProxy|mixed
     */
    protected function formatPaginatedResourceResponse($resource, string $message = '', $code = HttpResponse::HTTP_OK, array $headers = [], $option = 0)
    {
        $paginated = $resource->resource->toArray();

        $paginationInformation = [
            'meta' => [
                'pagination' => [
                    'total' => $paginated['total'] ?? null,
                    'count' => $paginated['to'] ?? null,
                    'per_page' => $paginated['per_page'] ?? null,
                    'current_page' => $paginated['current_page'] ?? null,
                    'total_pages' => $paginated['last_page'] ?? null,
                    'links' => [
                        'previous' => $paginated['prev'] ?? null,
                        'next' => $paginated['next_page_url'] ?? null,
                    ],
                ],
            ],
        ];

        $data = array_merge_recursive(['data' => $this->parseDataFrom($resource)], $paginationInformation);

        return tap(
            response()->json($this->formatData($data, $message, $code), $code, $headers, $option),
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
     * Parse data from JsonResource.
     *
     * @param JsonResource $data
     *
     * @return array
     */
    protected function parseDataFrom(JsonResource $data)
    {
        return array_merge_recursive($data->resolve(request()), $data->with(request()), $data->additional);
    }

    /**
     * Format JsonResource Data.
     *
     * @param JsonResource $resource
     * @param string       $message
     * @param int          $code
     * @param array        $headers
     * @param int          $option
     *
     * @return \Illuminate\Support\HigherOrderTapProxy|mixed
     */
    protected function formatResourceResponse($resource, string $message = '', $code = HttpResponse::HTTP_OK, array $headers = [], $option = 0)
    {
        return tap(
            response()->json($this->formatData($this->parseDataFrom($resource), $message, $code), $code, $headers, $option),
            function ($response) use ($resource) {
                $response->original = $resource->resource;

                $resource->withResponse(request(), $response);
            }
        );
    }

    /**
     * @param JsonResource|array|null $data
     * @param string                  $message
     * @param string                  $location
     *
     * @return \Illuminate\Http\JsonResponse|JsonResource
     */
    public function created($data = null, string $message = '', string $location = '')
    {
        $response = $this->success($data, $message, HttpResponse::HTTP_CREATED);
        if ($location) {
            $response->header('Location', $location);
        }

        return $response;
    }

    public function noContent(string $message = '')
    {
        return $this->success(null, $message, HttpResponse::HTTP_NO_CONTENT);
    }
}
