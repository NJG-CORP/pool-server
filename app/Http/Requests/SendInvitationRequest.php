<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SendInvitationRequest extends FormRequest
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
            'invited_id' => 'required|exists:users,id',
            'club_id' => 'required|exists:clubs,id',
            'meeting_at' => 'required|date_format:d.m.Y H:i:s'
        ];
    }
}
