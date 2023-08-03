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
        if (!class_exists($className)) {
            throw new \Exception("class `{$className}` doesn't exists");
        }

        $implements = class_implements($className);
        if (!is_array($implements)) {
            throw new \Exception("can't load `{$className}` interfaces");
        }

        if (!in_array(CanBeValidated::class, $implements, true)) {
            throw new \Exception("`{$className}` should implement CanBeValidated interface");
        }

        $this->className = $className;
        $this->setter = $setter ?? new ReflectionSetter();
    }

    public function check(mixed $value): mixed
    {
        if ($this->localization === null) {
            throw new \Exception('localization settings should be set');
        }

        if (is_object($value)) {
            $value = array($value);
        }

        if (!is_array($value)) {
            throw new EngineException($this->_('SHOULD_BE_ARRAY'));
        }

        /** @var CanBeValidated $tmp */
        $tmp = new $this->className();

        $mapper = new Mapper(Form::make($value), $tmp->getValidationConfig(), $this->localization);
        $result = $mapper->toObject($tmp, $this->setter);
        if ($result === null || !$result->hasErrors()) {
            return $tmp;
        }

        $viewErrors = [];
        foreach ($result->getErrors() as $k => $v) {
            $kErrors = implode(', ', $v);

            $viewErrors[$k] = sprintf('%s: %s', $k, $kErrors);
        }

        throw new EngineException($this->_('VALIDATION_ERROR', [
            'error' => implode(', ', $viewErrors),
        ]));
    }

    public function getDescription(): string
    {
        return 'object of specified class from array';
    }
}
