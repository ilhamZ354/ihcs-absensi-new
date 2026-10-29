<?php

namespace App\Exceptions;

use Exception;
use PDOException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use App\Services\Absensi\Exceptions\AccessPermissionDeniedException;
use App\Services\IHCS\Exceptions\SSOException;
use App\Jobs\TelegramJob;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        if($exception instanceof PDOException) {
            // TelegramJob::dispatch($exception->getMessage(), 'harapan');
        }else if($exception->getMessage() == "SSO: Session tidak valid") {
            return redirect(env('SSO_CHECKPOINT_URL').env('SSO_APP_CODE'));
        }else if($exception instanceof SSOException) {
            return redirect()->to('https://ihcs.ptpn4.co.id')->with('error', $exception->getMessage());            
        }else if($exception instanceof AccessPermissionDeniedException) {
            return redirect()->route('home')->with('error', $exception->getMessage());
        }
        return parent::render($request, $exception);
    }
}
