<?php
declare(strict_types=1);

namespace Warhammerdle\Controllers;

use InvalidArgumentException;
use Warhammerdle\Database\GeneralRepository;

abstract class Validation {
    protected array $data;
    protected array $errors = [];
    protected GeneralRepository $db;

    public function __construct(string $jsonInput) {
        $this->data = json_decode($jsonInput, true) ?? [];
        $this->db = new GeneralRepository;
    }

    // each child has to define their rules 
    abstract public function rules(): array;

    // custom validation for each child
    protected function customValidation(): void {}

    // main validation method
    public function validate(): bool {
        $rules = $this->rules();
        foreach ($rules as $field => $constraints) {
            $value = $this->data[$field] ?? null;

            foreach ($constraints as $constraint) {
                [$ruleName, $param] = $this->parseRule($constraint);
                
                $methodName = "validate" . ucfirst($ruleName);
                
                if (method_exists($this, $methodName)) {
                    if (!$this->$methodName($field, $value, $param)) {
                    }
                }
            }
        }
        $this->customValidation();
        
        return empty($this->errors);
    }

    protected function validateRequired($field, $value): bool {
        if ($value === null || (is_string($value) && trim($value) === "")) {
            $this->addError($field, "The '$field' field is mandatory.");
            return false;
        }
        return true;
    }

    protected function validateEmail($field, $value): bool {
        if ($value && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $this->addError($field, "The '$field' field must be a valid email address.");
            return false;
        }
        return true;
    }

    protected function validateMin($field, $value, $param): bool {
        if (strlen((string)$value) < (int)$param) {
            $this->addError($field, "The '$field' field must have at least $param characters.");
            return false;
        }
        return true;
    }

    // support methods
    private function parseRule(string $rule): array {
        $parts = explode(':', $rule, 2);
        return [$parts[0], $parts[1] ?? null];
    }

    private function addError(string $field, string $message): void {
        $this->errors[$field][] = $message;
    }

    public function getErrors(): array {
        return $this->errors;
    }

    public function getSafeData(): array {
        return $this->data;
    }
}