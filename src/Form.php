<?php

declare(strict_types=1);

namespace OValidator;

use OValidator\Interfaces\Form as FormInterface;

class Form implements FormInterface
{
    /** @var array<string, mixed> */
    private array $values;

    public function __construct()
    {
        $this->values = [];
    }

    /**
     * Fast Form creation
     * @param array<string, mixed> $values
     * @return FormInterface
     * @throws \Exception
     */
    public static function make(array $values): FormInterface
    {
        return (new self())->fromArray($values);
    }

    public function fromArray(array $data): FormInterface
    {
        foreach ($data as $k => $v) {
            if (!is_string($k)) {
                throw new \Exception('fromArray(): every key should be string');
            }

            $this->values[$k] = $v;
        }

        return $this;
    }

    public function export(): array
    {
        return $this->values;
    }
}
