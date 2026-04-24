<?php

namespace App\Rules\Shared;

use Illuminate\Contracts\Validation\Rule;

class UniquePerTenant implements Rule
{
    public function passes($attr, $value): bool { return true; }
    public function message(): string { return ''; }
}
