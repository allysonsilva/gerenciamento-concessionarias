<?php

namespace App\Initializers;

use Throwable;
use Illuminate\Support\Arr;
use Illuminate\Routing\Router;
use Illuminate\Support\Reflector;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Exceptions\Handler as FoundationExceptionHandler;

class ExceptionHandler extends FoundationExceptionHandler
{
    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            if (app()->bound('sentry') &&
                $this->shouldReport($e) &&
                ! app()->runningUnitTests()) {
                    if (Reflector::isCallable($shouldBeReportedCallable = [$e, 'shouldBeReported'])) {
                        return $this->container->call($shouldBeReportedCallable);
                    }

                    app('sentry')->captureException($e);

                    // If you wish to stop the propagation of the exception to the default logging stack,
                    // you may use the stop method when defining your reporting callback or return false from the callback
                    return false;
            }
        });
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $e
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $e)
    {
        if (method_exists($e, 'render') && $response = $e->render($request)) {
            return Router::toResponse($request, $response);
        }

        if ($e instanceof Responsable) {
            return $e->toResponse($request);
        }

        $e = $this->prepareException($this->mapException($e));

        if ($response = $this->renderViaCallbacks($request, $e)) {
            return $response;
        }

        return match (true) {
            $e instanceof ValidationException => $this->convertValidationExceptionToResponse($e, $request),
            default => $this->renderExceptionResponse($request, $e),
        };
    }

    /**
     * Create a response object from the given validation exception.
     *
     * @param  \Illuminate\Validation\ValidationException  $e
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function convertValidationExceptionToResponse(ValidationException $e, $request)
    {
        return response()->json([
            'error' => [
                'message' => $e->getMessage(),
                'errors' => $e->errors(),
            ],
        ], $e->status);
    }

    /**
     * Render a default exception response if any.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $e
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function renderExceptionResponse($request, Throwable $e)
    {
        return $this->prepareJsonResponse($request, $e);
    }

    /**
     * Convert the given exception to an array.
     *
     * @param  \Throwable  $e
     *
     * @return array{'error': array<mixed>}
     *
     * @phpstan-ignore-next-line
     */
    protected function convertExceptionToArray(Throwable $e): array
    {
        return [
            'error' => array_merge([
                'message' => config('app.debug') === true ? $e->getMessage() : __('messages.general exception message'),
            ], (function () use ($e): array {
                $data = [];

                if (config('app.debug')) {
                    return array_merge($data, [
                        'exception' => get_class($e),
                        'file' => $e->getFile(),
                        'line' => $e->getLine(),
                        'trace' => collect($e->getTrace())->map(fn ($trace) => Arr::except($trace, ['args']))->all(),
                    ]);
                }

                return $data;
            })()),
        ];
    }
}
