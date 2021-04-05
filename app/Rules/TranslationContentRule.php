<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class TranslationContentRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $value = json_decode($value, true);
        $fallback_locale = config('translatable.fallback_locale');
        $supliedLocales = array_keys($value);

        if (!in_array($fallback_locale, $supliedLocales)) {
            return false;
        }

        if(!isset($value[$fallback_locale]['content'])) {
            return false;
        }

        if (strlen($value[$fallback_locale]['content']) < 3) {
            return false;
        }

        return true;

    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The content :attribute in eng required min 3 charters.';
    }
}
