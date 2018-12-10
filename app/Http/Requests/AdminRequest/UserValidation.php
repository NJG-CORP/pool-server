<?php

namespace App\Http\Requests\AdminRequest;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
class UserValidation extends FormRequest
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
            'item'=>'required',
            'name'=>'required',
             'surname'=>'required',
              'age'=>'required',
               'mail'=>'required'
        ];
    }
   public function messages()
    {
      return [
      'item.required' => 'Someting going wrong.',

              'name.required'=>'name can not empty.',
              'surname.required'=>'surname can not empty.',
              'age.required'=>'age can not empty.',
              'mail.required'=>'mail can not empty.'
             
            ];
    }
}
