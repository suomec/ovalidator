<?php

declare(strict_types=1);

namespace OValidator\Engines;

use OValidator\Exceptions\EngineException;
use OValidator\Form;
use OValidator\Interfaces\CanBeValidated;
use OValidator\Interfaces\Setter;
use OValidator\Mapper;
use OValidator\Objects\ValidatorBase;
use OValidator\Setters\ReflectionSetter;

/**
 * Make object of desired type from input array
 */
class VObject extends ValidatorBase
{
    private string $className;
    private Setter $setter;

    /**
     * @param string $className Should implement interface CanBeValidated
     * @param Setter|null $setter Fields setter
     */
    public function __construct(string $className, ?Setter $setter = null)
    {
        $implements = class_implements($className);
        if (!is_array($implements)) {
            throw new \Exception($this->_("can't load {class} interfaces", ['class' => $className]));
        }

        if (!in_array(CanBeValidated::class, $implements)) {
            throw new \Exception($this->_('{class} should implement CanBeValidated interface', [
                'class' => $className,
            ]));
        }

        $this->className = $className;
        $this->setter = $setter !== null ? $setter : new ReflectionSetter();
    }

    public function check(mixed $value): mixed
    {
        if (is_object($value)) {
            $value = array($value);
        }

        if (!is_array($value)) {
            throw new EngineException($this->_('value should be array'));
        }

        /** @var CanBeValidated $tmp */
        $tmp = new $this->className();

        $mapper = new Mapper((new Form())->fromArray($value), $tmp->getValidationConfig(), $this->i18n);
        $result = $mapper->toObject($tmp, $this->setter);
        if ($result === null || !$result->hasErrors()) {
            return $tmp;
        }

        $viewErrors = [];
        foreach ($result->getErrors() as $k => $v) {
            $kErrors = implode(', ', $v);

            $viewErrors[$k] = sprintf('%s: %s', $k, $kErrors);
        }

        throw new EngineException($this->_('object validation error[{error}]', [
            'error' => implode(', ', $viewErrors),
        ]));
    }

    public function getDescription(): string
    {
        return 'object from array';
    }
}
