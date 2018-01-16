<?php

namespace App\Http\Controllers;

use App\Exceptions\ControllableException;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * @var Request
     */
    protected $request = null;

    /**
     * @param $validationRules
     * @throws ControllableException
     */
    protected function validateRequestData($validationRules){
        $req = $this->request->all();
        $validator = \Validator::make($req, $validationRules);
        if ( $validator->errors()->count() ){
            throw new ControllableException(
                $validator->errors()->first()
            );
        }
    }
}
