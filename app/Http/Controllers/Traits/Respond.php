<?php

namespace App\Http\Controllers\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Support\Arrayable;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

trait Respond
{
    /**
     * The HTTP response headers.
     *
     * @var array
     */
    protected array $respondHeaders = [];

    /**
     * The HTTP response meta data.
     *
     * @var array
     */
    protected array $respondMeta = [];

    /**
     * The HTTP response data.
     *
     * @var mixed
     */
    protected array $respondData = [];

    /**
     * The HTTP additional data.
     *
     * @var mixed
     */
    protected array $additionalData = [];

    /**
     * The HTTP response status code.
     *
     * @var int
     */
    protected $respondStatusCode = HttpResponse::HTTP_OK;

    protected function addHeadersInResponse(array $headers): self
    {
        $this->respondHeaders = $headers;

        return $this;
    }

    protected function respondMeta(array $meta): self
    {
        $this->respondMeta = $meta;

        return $this;
    }

    protected function respondWithData(array $data): self
    {
        $this->respondData = $data;

        return $this;
    }

    protected function additionalData(array $data): self
    {
        $this->additionalData = $data;

        return $this;
    }

    protected function respondStatusCode(int $statusCode): self
    {
        $this->respondStatusCode = $statusCode;

        return $this;
    }

    /**
     * Build the response for standard.
     *
     * @param string $message
     * @param int $statusCode
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respond(string $message = '', int $statusCode = HttpResponse::HTTP_OK): JsonResponse
    {
        return $this
                ->respondWithData([
                    'message' => $message
                ])
                ->respondStatusCode($statusCode)
                ->respondStandard();
    }

    protected function respondData(array $data, int $statusCode = HttpResponse::HTTP_OK): JsonResponse
    {
        return $this->respondWithData($data)
                    ->respondStatusCode($statusCode)
                    ->respondStandard();
    }

    /**
     * Build the response.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondStandard(): JsonResponse
    {
        $response = [];

        $response['data'] = $this->respondData;

        if ($this->respondData instanceof Arrayable) {
            $response['data'] = $this->respondData->toArray();
        }

        $response['data'] = array_merge($response['data'], $this->additionalData);

        if (! empty($this->respondMeta)) {
            $response['meta'] = $this->respondMeta;
        }

        return response()->json($response, $this->respondStatusCode, $this->respondHeaders);
    }
}
