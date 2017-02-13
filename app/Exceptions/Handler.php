<?php namespace Backend\Exceptions;

use Backend\Facades\Log;
use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Session\TokenMismatchException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        'Symfony\Component\HttpKernel\Exception\HttpException',
        'Illuminate\Session\TokenMismatchException',
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $e
     * @return void
     */
    public function report(Exception $e)
    {
        return parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {
        if ($e instanceof TokenMismatchException) {
            Log::warning($e->getMessage());
            auth()->logout();
            session()->clear();
            \Noty::warn('CSRF token mismatch, please login again.');
            if ($request->ajax()) {
                return response('', 412);
            } else {
                return redirect('/');
            }
        }

        if ($this->isHttpException($e)) {
            return $this->renderHttpException($e);
        } else {
            return parent::render($request, $e);
        }
    }
}
