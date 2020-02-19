<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class MobileRule implements Rule
{
    public function passes($attribute, $value)
    {
        return preg_match("/^(0)[5-8]{1}[0-9]{8}$/", $value);
    }

    public function message()
    {
        return 'Ce numéro cellulaire n\'as pas une forme valide';
    }
}
