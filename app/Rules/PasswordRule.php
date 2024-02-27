<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class PasswordRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $regex = '/^(?!.*?\n)(?=.*?\d)(?=.*?[A-Z])(?=.*?[a-z]).*$/';
        $result = preg_match($regex, $value);
        if ($result !== 1 && !is_null($value)) {
            $fail(__('validation.password'));
        }
    }
}
