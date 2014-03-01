<?php namespace Validator;

class Validator {

    /**
    * @functions list
    *
    * public isValid()
    * public validate()
    * public getErrorMessages()
    * public getOwnerCode()
    * public getProductGroupCode()
    * public getRegistrationDigit()
    * public getCheckDigit()
    * public generate()
    * public createCheckDigit()
    * public clearErrors()
    * protected buildCheckDigit()
    * protected identify()
    *
    **/

    private $alphabetNumerical = array(
        'A' => 10, 'B' => 12, 'C' => 13, 'D' => 14, 'E' => 15, 'F' => 16, 'G' => 17, 'H' => 18, 'I' => 19,
        'J' => 20, 'K' => 21, 'L' => 23, 'M' => 24, 'N' => 25, 'O' => 26, 'P' => 27, 'Q' => 28, 'R' => 29,
        'S' => 30, 'T' => 31, 'U' => 32, 'V' => 34, 'W' => 35, 'X' => 36, 'Y' => 37, 'Z' => 38
    );
    protected $pattern = '/^([A-Z]{3})(U|J|Z)(\d{6})(\d)$/';
    protected $patternWithoutCheckDigit = '/^([A-Z]{3})(U|J|Z)(\d{6})$/';
    protected $errorMessages = array();
    protected $ownerCode = array();
    protected $productGroupCode;
    protected $registrationDigit = array();
    protected $checkDigit;
    protected $containerNumber;

    /**
     * Check if the container has a valid container code
     *
     * @return boolean
     */
    public function isValid($containerNumber)
    {
        $valid = $this->validate($containerNumber);
        if (empty($this->errorMessages)) {
            return true;
        }
        return false;
    }

    public function validate($containerNumber)
    {
        $matches = array();

        if (!empty($containerNumber) && is_string($containerNumber)) {
            $matches = $this->identify($containerNumber);

            if (count($matches) !== 5) {
                $this->errorMessages[] = 'The container number is invalid';
            } else {
                $checkDigit = $this->buildCheckDigit($matches);

                if ($this->checkDigit != $checkDigit) {
                    $this->errorMessages[] = 'The check digit does not match';
                    $matches = array();
                }
            }
        } else {
            $this->errorMessages = array('The container number must be a string');
        }
        return $matches;
    }

    public function getErrorMessages()
    {
        return $this->errorMessages;
    }

    public function getOwnerCode()
    {
        if (empty($this->ownerCode)) {
            $this->errorMessages[] = 'You must call validate or isValid first';
        }
        return $this->ownerCode;
    }

    public function getProductGroupCode()
    {
        if (empty($this->productGroupCode)) {
            $this->errorMessages[] = 'You must call validate or isValid first';
        }
        return $this->productGroupCode;
    }

    public function getRegistrationDigit()
    {
        if (empty($this->registrationDigit)) {
            $this->errorMessages[] = 'You must call validate or isValid first';
        }
        return $this->registrationDigit;
    }

    public function getCheckDigit()
    {
        if (empty($this->checkDigit)) {
            $this->errorMessages[] = 'You must call validate or isValid first';
        }
        return $this->checkDigit;
    }

    public function generate($ownerCode, $productGroupCode, $from = 0, $to = 999999)
    {

        $alphabetCode = strtoupper($ownerCode . $productGroupCode);
        $containers_no = array();

        if (is_string($alphabetCode) && strlen($ownerCode) === 3 && strlen($productGroupCode) === 1) {
            $containers_no = array();
            $current_container_no = '';
            $current_container_check_digit = '';

            if (($from >= 0) && ($to < 1000000) && (($to - $from) > 0)) {
                for ( $i = $from; $i <= $to; $i++) {
                    $current_container_no = $alphabetCode . str_pad($i, 6, '0', STR_PAD_LEFT);
                    $current_container_check_digit = $this->createCheckDigit($current_container_no);

                    if ($current_container_check_digit < 0) {
                        $this->errorMessages[] = 'Error generating container number at number ' . $i;
                        return $containers_no;
                    }

                    $containers_no[$i] = $current_container_no . $current_container_check_digit;
                }
            } else {
                $this->errorMessages[] = 'Invalid number to generate, minimal is 0 and maximal is 999999';
            }

        } else {
            $this->errorMessages[] = 'Invalid owner code or product group code';
        }

        return $containers_no;
    }

    public function createCheckDigit($containerNumber)
    {
        $checkDigit = -1;
        if (!empty($containerNumber) && is_string($containerNumber)) {
            $matches = $this->identify( $containerNumber, true );

            if (count($matches) !== 4 || isset($mathes[4])) {
                $this->errorMessages[] = 'Invalid container number';
            } else {
                $checkDigit = $this->buildCheckDigit($matches);
                if ($checkDigit < 0) {
                    $this->errorMessages[] = 'Invalid container number';
                }
            }
        } else {
            $this->errorMessages[] = 'Container number must be a string';
        }
        return $checkDigit;
    }

    public function clearErrors()
    {
        $this->errorMessages = array();
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
        $numericalOwnerCode = array();
        for ($i = 0; $i < count($this->ownerCode); $i++) {
            $numericalOwnerCode[$i] = $this->alphabetNumerical[$this->ownerCode[$i]];
        }
        $numericalOwnerCode[] = $this->alphabetNumerical[$this->productGroupCode];

        // merge numerical owner code with registration digit
        $numericalCode = array_merge($numericalOwnerCode, $this->registrationDigit);
        $sumDigit = 0;

        // check six-digit registration number and last check digit
        for ($i = 0; $i < count($numericalCode); $i++) {
            $sumDigit += $numericalCode[$i] * pow(2, $i);
        }

        $sumDigitDiff = floor($sumDigit / 11) * 11;
        $checkDigit = $sumDigit - $sumDigitDiff;
        return ($checkDigit == 10) ? 0 : $checkDigit;
    }

    protected function identify($containerNumber, $withoutCheckDigit = false)
    {
        $this->clearErrors();

        if ($withoutCheckDigit) {
            preg_match($this->patternWithoutCheckDigit, strtoupper($containerNumber), $matches);
        } else {
            preg_match($this->pattern, strtoupper($containerNumber), $matches);
        }
        return $matches;
    }
}