<?php
/**
 * Created by PhpStorm.
 * User: tooyz
 * Date: 11.01.2018
 * Time: 1:38
 */

namespace App\Exceptions;


use App\Http\Responder;
use Throwable;

class ControllableException extends \Exception
{
    private $data = null;

    public function __construct(string $message = "", $data = null, $code = 500)
    {
        parent::__construct($message, $code);
        $this->data = $data;
    }

    /**
     * Report the exception.
     *
     * @return void
     */
    public function report()
    {
        //
    }

    /**
     * Render the exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function render($request)
    {
        return (new Responder())->errorResponse(
            $this->getMessage(), $this->getCode(), $this->data
        );
    }
}
