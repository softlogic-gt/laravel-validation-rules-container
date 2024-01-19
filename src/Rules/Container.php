<?php
namespace SoftlogicGT\ValidationRulesContainer\Rules;

use Illuminate\Contracts\Validation\Rule;

class Container implements Rule
{
    protected $container;
    protected $attribute;
    protected $checkDigit;
    protected $productGroupCode;
    protected $registrationDigit = [];
    protected $ownerCode         = [];
    protected $pattern           = '/^([A-Z]{3})(U|J|Z)(\d{6})(\d)$/';
    private $alphabetNumerical   = [
        'A' => 10, 'B' => 12, 'C' => 13, 'D' => 14, 'E' => 15, 'F' => 16, 'G' => 17, 'H' => 18, 'I' => 19,
        'J' => 20, 'K' => 21, 'L' => 23, 'M' => 24, 'N' => 25, 'O' => 26, 'P' => 27, 'Q' => 28, 'R' => 29,
        'S' => 30, 'T' => 31, 'U' => 32, 'V' => 34, 'W' => 35, 'X' => 36, 'Y' => 37, 'Z' => 38,
    ];

    public function __construct(bool $container = true)
    {
        $this->container = $container;
    }

    public function nullable(): self
    {
        $this->container = true;

        return $this;
    }

    public function passes($attribute, $value): bool
    {
        $this->attribute = $attribute;

        if (empty($value)) {
            return true;
        }

        if (!is_string($value)) {
            return false;
        }

        $matches = [];
        preg_match($this->pattern, strtoupper($value), $matches);

        if (count($matches) !== 5) {
            return false;
        }

        $checkDigit = $this->buildCheckDigit($matches);

        if ($this->checkDigit != $checkDigit) {
            return false;
        }

        return true;

    }

    protected function buildCheckDigit($matches)
    {

        if (isset($matches[1])) {
            $this->ownerCode = str_split($matches[1]);
        }
        if (isset($matches[2])) {
            $this->productGroupCode = $matches[2];
        }
        if (isset($matches[3])) {
            $this->registrationDigit = str_split($matches[3]);
        }
        if (isset($matches[4])) {
            $this->checkDigit = $matches[4];
        }

        // convert owner code + product group code to its numerical value
        $numericalOwnerCode = [];
        for ($i = 0; $i < count($this->ownerCode); $i++) {
            $numericalOwnerCode[$i] = $this->alphabetNumerical[$this->ownerCode[$i]];
        }
        $numericalOwnerCode[] = $this->alphabetNumerical[$this->productGroupCode];

        // merge numerical owner code with registration digit
        $numericalCode = array_merge($numericalOwnerCode, $this->registrationDigit);
        $sumDigit      = 0;

        // check six-digit registration number and last check digit
        for ($i = 0; $i < count($numericalCode); $i++) {
            $sumDigit += $numericalCode[$i] * pow(2, $i);
        }

        $sumDigitDiff = floor($sumDigit / 11) * 11;
        $checkDigit   = $sumDigit - $sumDigitDiff;

        return ($checkDigit == 10) ? 0 : $checkDigit;
    }

    public function message(): string
    {
        return __('validationRulesContainer::messages.container', [
            'attribute' => $this->attribute,
        ]);
    }
}
