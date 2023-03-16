<?php

declare(strict_types=1);

namespace OValidator\Engines;

use OValidator\Exceptions\EngineException;
use OValidator\Objects\ValidatorBase;

/**
 * Creates backed enum from its string value. Returns instance of enum object
 */
class VEnum extends ValidatorBase
{
    /** @var \ReflectionEnumBackedCase[] */
    private array $cases;
    /** @var array<mixed> */
    private array $disallowCases;

    /**
     * @param string $enumClass Enum class name
     * @param array<mixed> $disallowCases List of enum values which not allowed
     */
    public function __construct(string $enumClass, array $disallowCases = [])
    {
        if (!enum_exists($enumClass)) {
            throw new \Exception($this->_('passed enum class name not exists'));
        }

        $this->cases = (new \ReflectionEnum($enumClass))->getCases();
        $this->disallowCases = $disallowCases;
    }

    public function check(mixed $value): mixed
    {
        foreach ($this->cases as $case) {
            foreach ($this->disallowCases as $disallowCase) {
                //@phpstan-ignore-next-line
                if ($disallowCase->name === $case->getName()) {
                    continue 2;
                }
            }

            if ($case->getName() === $value) {
                return $case->getValue();
            }
        }

        throw new EngineException($this->_('case not found in: {names}', [
            'names' => implode(', ', $this->getCasesNames()),
        ]));
    }

    public function getDescription(): string
    {
        return 'Enum cases: ' . implode(', ', $this->getCasesNames());
    }

    /**
     * @return string[] String names of enum constants
     */
    private function getCasesNames(): array
    {
        $values = [];
        foreach ($this->cases as $case) {
            foreach ($this->disallowCases as $disallowCase) {
                //@phpstan-ignore-next-line
                if ($disallowCase->name === $case->getName()) {
                    continue 2;
                }
            }

            $values[] = $case->getName();
        }

        return $values;
    }
}
