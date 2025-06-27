<?php
class validation
{
    private $error = false;
    private $messageBag = [];

    public function validate(array $request, array $rulesPair, array $fieldAlias = [])
    {
        foreach ($rulesPair as $key => $val) {
            $rules = explode('|', $val);
            foreach ($rules as $rule_name) {
                $alias = (isset($fieldAlias[$key]) && $fieldAlias[$key] != '') ? $fieldAlias[$key] : $key;
                $this->hasMetCondition($request, $key, $rule_name, $alias);
            }
        }
        return ['error' => $this->error, 'messages' => $this->messageBag];
    }

    public function hasMetCondition($request, $key, $rule_to_validate, $alias)
    {
        $val = isset($request[$key]) ? $request[$key] : '';

        // Handle "matches:fieldname"
        if (strpos($rule_to_validate, 'matches:') === 0) {
            $field_to_match = explode(':', $rule_to_validate)[1];
            $val_to_match = isset($request[$field_to_match]) ? $request[$field_to_match] : '';
            if ($val !== $val_to_match) {
                $this->error = true;
                $this->messageBag[] = "$alias does not match $field_to_match";
            }
            return;
        }

        // Handle "min:length"
        if (strpos($rule_to_validate, 'min:') === 0) {
            $min_length = intval(explode(':', $rule_to_validate)[1]);
            if (strlen($val) < $min_length) {
                $this->error = true;
                $this->messageBag[] = "$alias must be at least $min_length characters long.";
            }
            return;
        }

        // Basic rules without parameters
        if (strpos($rule_to_validate, ':') === false) {
            if ($rule_to_validate == 'required') {
                if ($key == "*") {
                    foreach ($request as $row => $v) {
                        $this->checkRequired($v, $alias);
                    }
                } else {
                    $this->checkRequired($val, $alias);
                }
            }

            if ($rule_to_validate == 'int') {
                if (!is_numeric($val)) {
                    $this->error = true;
                    $this->messageBag[] = "$alias field must be an integer";
                }
            }

            if ($rule_to_validate == 'email') {
                $email = filter_var($val, FILTER_SANITIZE_EMAIL);
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $this->error = true;
                    $this->messageBag[] = "$alias field must be a valid email";
                }
            }
        }
    }

    public function checkRequired($value, $alias)
    {
        if ($value === "" || $value === null) {
            $this->error = true;
            $this->messageBag[] = "$alias field is required.";
        }
    }
}
