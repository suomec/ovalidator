<?php

declare(strict_types=1);

namespace OValidator\Engines;

use OValidator\Exceptions\ValidatorException;
use OValidator\Form;
use OValidator\Interfaces\CanBeValidated;
use OValidator\Interfaces\Setter;
use OValidator\Mapper;
use OValidator\Objects\ValidatorBase;
use OValidator\Setters\PublicProperties;

/**
 * Field should be object with class implementing interface CanBeValidated
 */
class VObject extends ValidatorBase
{
    private string $className;
    private Setter $setter;

    public function __construct(string $className, ?Setter $setter = null)
    {
        $tmp = new $className();
        if (!($tmp instanceof CanBeValidated)) {
            throw new \Exception($this->_('{class} should implement CanBeValidated interface', [
                'class' => $className,
            ]));
        }

        $this->className = $className;
        $this->setter = $setter !== null ? $setter : new PublicProperties();
    }

    public function check(mixed $value): mixed
    {
        if (is_object($value)) {
            $value = array($value);
        }

        if (!is_array($value)) {
            throw new ValidatorException($this->_('value should be array'));
        }

        /** @var CanBeValidated $tmp */
        $tmp = new $this->className();

        $mapper = new Mapper((new Form())->fromArray($value), $tmp->getValidationConfig());
        $result = $mapper->toObject($tmp, $this->setter);
        if ($result === null || !$result->hasErrors()) {
            return $tmp;
        }

        $viewErrors = [];
        foreach ($result->getErrors() as $k => $v) {
            $viewErrors[$k] = implode(', ', $v);
        }

        throw new ValidatorException($this->_('object validation error: {error}', [
            'error' => implode(', ', $viewErrors),
        ]));
    }

    public function getDescription(): string
    {
        return 'should be object';
    }
}
