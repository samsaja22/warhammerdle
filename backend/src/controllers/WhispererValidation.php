<?php
declare(strict_types=1);

namespace Warhammerdle\Controllers;

use Warhammerdle\Controllers\Validation;

class WhispererValidation extends Validation
{
    public function __construct(string $payload)
    {
        parent::__construct($payload);
    }

    public function rules(): array {
        return [
            'whisper' => ['required', 'min:1']
        ];
    }
}