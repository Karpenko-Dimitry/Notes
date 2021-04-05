<?php

namespace App\Http\Requests\Api\Note;

use App\Rules\TranslationContentRule;
use App\Rules\TranslationTitleRule;
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
//            'translations' => [
//                'required', new TranslationTitleRule('title', 'required|min:3'), new TranslationContentRule(),
//            ],
            'translations.en.title' => 'required|min:3',
            'translations.en.content' => 'required|min:3',
            'category' => 'required',
        ];
    }
}
