<?php
/**
 * Input Validation Helper
 * Validates and sanitizes user input
 */

class Validator
{
    private array $data;
    private array $errors = [];
    private array $validated = [];

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public static function make(array $data): self
    {
        return new self($data);
    }

    public function validate(array $rules): self
    {
        foreach ($rules as $field => $fieldRules) {
            $ruleList = is_string($fieldRules) ? explode('|', $fieldRules) : $fieldRules;
            $value = $this->data[$field] ?? null;

            foreach ($ruleList as $rule) {
                $this->applyRule($field, $value, $rule);
            }

            if (!isset($this->errors[$field])) {
                $this->validated[$field] = $value;
            }
        }

        return $this;
    }

    private function applyRule(string $field, mixed $value, string $rule): void
    {
        $parts = explode(':', $rule, 2);
        $ruleName = $parts[0];
        $param = $parts[1] ?? null;

        $label = $this->formatFieldName($field);

        switch ($ruleName) {
            case 'required':
                if (empty($value) && $value !== '0') {
                    $this->addError($field, "{$label} is required");
                }
                break;

            case 'email':
                if (!empty($value) && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $this->addError($field, "{$label} must be a valid email address");
                }
                break;

            case 'phone':
                if (!empty($value) && !$this->isValidPhone($value)) {
                    $this->addError($field, "{$label} must be a valid phone number");
                }
                break;

            case 'min':
                if (!empty($value) && strlen($value) < (int) $param) {
                    $this->addError($field, "{$label} must be at least {$param} characters");
                }
                break;

            case 'max':
                if (!empty($value) && strlen($value) > (int) $param) {
                    $this->addError($field, "{$label} must not exceed {$param} characters");
                }
                break;

            case 'numeric':
                if (!empty($value) && !is_numeric($value)) {
                    $this->addError($field, "{$label} must be a number");
                }
                break;

            case 'integer':
                if (!empty($value) && !filter_var($value, FILTER_VALIDATE_INT)) {
                    $this->addError($field, "{$label} must be an integer");
                }
                break;

            case 'in':
                $options = explode(',', $param);
                if (!empty($value) && !in_array($value, $options)) {
                    $this->addError($field, "{$label} must be one of: " . implode(', ', $options));
                }
                break;

            case 'confirmed':
                $confirmField = $field . '_confirmation';
                if ($value !== ($this->data[$confirmField] ?? null)) {
                    $this->addError($field, "{$label} confirmation does not match");
                }
                break;

            case 'regex':
                if (!empty($value) && !preg_match($param, $value)) {
                    $this->addError($field, "{$label} format is invalid");
                }
                break;

            case 'alpha':
                if (!empty($value) && !ctype_alpha($value)) {
                    $this->addError($field, "{$label} must contain only letters");
                }
                break;

            case 'alphanumeric':
                if (!empty($value) && !ctype_alnum($value)) {
                    $this->addError($field, "{$label} must contain only letters and numbers");
                }
                break;

            case 'year':
                $year = (int) $value;
                $currentYear = (int) date('Y');
                if (!empty($value) && ($year < 1900 || $year > $currentYear + 1)) {
                    $this->addError($field, "{$label} must be a valid year");
                }
                break;

            case 'unique':
                [$table, $column] = explode(',', $param);
                if (!empty($value) && $this->existsInDatabase($table, $column, $value)) {
                    $this->addError($field, "{$label} is already taken");
                }
                break;

            case 'exists':
                [$table, $column] = explode(',', $param);
                if (!empty($value) && !$this->existsInDatabase($table, $column, $value)) {
                    $this->addError($field, "{$label} does not exist");
                }
                break;
        }
    }

    private function isValidPhone(string $phone): bool
    {
        // Clean phone number
        $phone = preg_replace('/[^0-9+]/', '', $phone);

        // Georgian phone format: starts with 5 and has 9 digits, or +995 prefix
        // Also accept international formats
        return preg_match('/^(\+?995)?5\d{8}$/', $phone) ||
            preg_match('/^\+?[1-9]\d{6,14}$/', $phone);
    }

    private function existsInDatabase(string $table, string $column, mixed $value): bool
    {
        $sql = "SELECT COUNT(*) as count FROM {$table} WHERE {$column} = ?";
        $result = Database::fetchOne($sql, [$value]);
        return ($result['count'] ?? 0) > 0;
    }

    private function formatFieldName(string $field): string
    {
        return ucfirst(str_replace('_', ' ', $field));
    }

    public function addError(string $field, string $message): void
    {
        if (!isset($this->errors[$field])) {
            $this->errors[$field] = [];
        }
        $this->errors[$field][] = $message;
    }

    public function fails(): bool
    {
        return !empty($this->errors);
    }

    public function passes(): bool
    {
        return empty($this->errors);
    }

    public function errors(): array
    {
        return $this->errors;
    }

    public function firstError(?string $field = null): ?string
    {
        if ($field !== null) {
            return $this->errors[$field][0] ?? null;
        }

        foreach ($this->errors as $fieldErrors) {
            return $fieldErrors[0] ?? null;
        }

        return null;
    }

    public function allErrors(): array
    {
        $all = [];
        foreach ($this->errors as $fieldErrors) {
            $all = array_merge($all, $fieldErrors);
        }
        return $all;
    }

    public function validated(): array
    {
        return $this->validated;
    }

    public static function sanitize(mixed $value): mixed
    {
        if (is_array($value)) {
            return array_map([self::class, 'sanitize'], $value);
        }

        if (is_string($value)) {
            $value = trim($value);
            $value = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
        }

        return $value;
    }

    public static function sanitizePhone(string $phone): string
    {
        // Remove all non-digit characters except +
        $phone = preg_replace('/[^0-9+]/', '', $phone);

        // If starts with 5 and has 9 digits, assume Georgian number
        if (preg_match('/^5\d{8}$/', $phone)) {
            return '+995' . $phone;
        }

        // If starts with 995, add +
        if (preg_match('/^995\d{9}$/', $phone)) {
            return '+' . $phone;
        }

        return $phone;
    }
}
