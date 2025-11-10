<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class DecimalRule implements ValidationRule
{
    protected $decimals;

    public function __construct($decimals = 2)
    {
        $this->decimals = $decimals;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! preg_match('/^\d+(\.\d{1,'.$this->decimals.'})?$/', $value)) {
            $fail('The :attribute number with up to '.$this->decimals.' decimal places.');
        }
    }
}
