<?php

namespace App\Http\Requests;

use http\Env\Request;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class CreateMessageRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules(): array
    {
            return [
                "conversation_id"=> "required_without:user_id|exists:conversations,id",
                "user_id"=> "required_without:conversation_id|exists:users,id",
                "type_message"=>"required|in:text,attachment",
                'message' =>[ Rule::requiredIf(function ()  {
                    return $this->type_message === 'text';
                }),'string'],
                'attachment' =>[ Rule::requiredIf(function ()  {
                    return $this->type_message === 'attachment';
                }),'file'],



            ];

    }

}
