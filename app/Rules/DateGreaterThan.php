<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class DateGreaterThan implements Rule
{
    private $otherField;

    public function __construct($otherField)
    {
        $this->otherField = $otherField;
    }

    public function passes($attribute, $value)
    {
        $otherValue = request($this->otherField);

        if (!empty($otherValue) && !empty($value)) {
            return strtotime($value) >= strtotime($otherValue);
        }

        // If either date is empty, consider it as valid to allow for optional fields.
        return true;
    }

    public function message()
    {
        return 'The :attribute Date must be greater than or equal to the ' . $this->otherField . ' Date';
    }
}
