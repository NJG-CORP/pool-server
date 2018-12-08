<?php

namespace App\Http\Requests\AdminRequest;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
class ClubValidation extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

  
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
     
    public function rules()
    {
        return [
              'title'=>'required',
              'pool'=>'required',
              'russian'=>'required',
              'snooker'=>'required',
              'cannon'=>'required',
              'kitchen'=>'required',
              'des'=>'required',
              'mob'=>'required',
              'location'=>'required'
        ];
    }
    public function messages()
    {
      return ['title.required' => 'Title can not empty.',

              'pool.required'=>'Pool can not empty.',
              'russian.required'=>'Russian can not empty.',
              'snooker.required'=>'Snooker can not empty.',
              'cannon.required'=>'Cannon can not empty.',
              'kitchen.required'=>'Kitchen can not empty.',
                'des.required'=>'Description can not empty.',
                 'mob.required'=>'Phone number can not empty.',
                  'location.required'=>'Location can not empty.'

            ];
    }
}
