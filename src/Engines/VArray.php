<?php

declare(strict_types=1);

namespace OValidator\Engines;

use OValidator\Exceptions\ValidatorException;
use OValidator\Interfaces\Validator;
use OValidator\Objects\ValidatorBase;

/**
 * Field should be array of elements
 */
class VArray extends ValidatorBase
{
    /** @var null|Validator[] */
    private ?array $cellValidators;
    private bool $onlyUnique;
    private bool $keepOriginalKeys;

    /**
     * @param Validator[]|null $cellValidators Every item validators
     * @param bool $onlyUnique Keep only unique items of array
     * @param bool $keepOriginalKeys Keep original keys of array
     */
    public function __construct(?array $cellValidators = null, bool $onlyUnique = false, bool $keepOriginalKeys = false)
    {
        if ($cellValidators !== null && count($cellValidators) === 0) {
            throw new \Exception($this->_('should be at least one validator for array item (validators != null)'));
        }

        if (is_array($cellValidators)) {
            foreach ($cellValidators as $validator) {
                if (!($validator instanceof Validator)) {
                    throw new \Exception($this->_('every validator should be instance of Validator interface'));
                }
            }
        }

        $this->cellValidators = $cellValidators;
        $this->onlyUnique = $onlyUnique;
        $this->keepOriginalKeys = $keepOriginalKeys;
    }

    public function check(mixed $value): mixed
    {
        if (!is_array($value)) {
            throw new ValidatorException($this->_('should be array'));
        }

        if (!$this->keepOriginalKeys) {
            $value = array_values($value);
        }

        if (is_array($this->cellValidators)) {
            foreach ($value as $index => $cell) {
                $currentValue = $cell;

                foreach ($this->cellValidators as $validator) {
                    $currentValue = $validator->check($currentValue);
                }

                $value[$index] = $currentValue;
            }
        }

        if ($this->onlyUnique) {
            $value = array_values(array_unique($value, SORT_REGULAR));
        }

        return $value;
    }

    public function getDescription(): string
    {
        $suffix = '';

        if (is_array($this->cellValidators)) {
            $descriptions = [];
            foreach ($this->cellValidators as $v) {
                $descriptions[] = $v->getDescription();
            }

            $suffix = ". Items validators: '" . implode("', '", $descriptions) . "'";
        }

        return 'array of elements'. $suffix;
    }
}
