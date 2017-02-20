<?php namespace Backend\Exceptions;

use Backend\Facades\Log;
use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Session\TokenMismatchException;
use Symfony\Component\HttpFoundation\Response;

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
        'PDOException',
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
     * @return Response
     */
    public function render($request, Exception $e)
    {
        if ($e instanceof TokenMismatchException) {
            Log::warning('CSRF token mismatch', $request->all());
            auth()->logout();
            session()->flush();
            \Noty::warn('CSRF token mismatch, please login again.');
            if ($request->ajax()) {
                return response('', Response::HTTP_PRECONDITION_FAILED);
            } else {
                return redirect('/');
            }
        }

        if ($e instanceof \PDOException) {
            Log::error($e->getMessage(), $request->all());
            return redirect('/');
        }

        if ($this->isHttpException($e)) {
            return $this->renderHttpException($e);
        } else {
            return parent::render($request, $e);
        }
    }
}
