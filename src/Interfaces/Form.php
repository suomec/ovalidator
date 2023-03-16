<?php

declare(strict_types=1);

namespace OValidator\Interfaces;

/**
 * Input container
 */
interface Form
{
    /**
     * Add input variables from user array
     * @param array<string, mixed> $data
     * @return Form
     */
    public function fromArray(array $data): Form;

    /**
     * Get user data
     * @return array<string, mixed> All input data
     */
    public function export(): array;
}
