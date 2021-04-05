<?php

namespace App\Http\Requests\Note;

use Illuminate\Foundation\Http\FormRequest;

class StoreNoteRequest extends FormRequest
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
            'en_title'     => 'required|min:3',
            'en_content'   => 'required|min:5',
            'category' => 'required',
            'public'    => 'nullable|boolean',
//            'user_file.*' => 'nullable|file',
        ];
    }
}
