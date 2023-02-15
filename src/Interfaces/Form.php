<?php

declare(strict_types=1);

namespace OValidator\Interfaces;

use Psr\Http\Message\ServerRequestInterface;

/**
 * Input container
 */
interface Form
{
    /**
     * Add input variables from request
     * @param ServerRequestInterface $request
     * @return Form
     */
    public function fromRequest(ServerRequestInterface $request): Form;

    /**
     * Add input variables from user array
     * @param array<string, mixed> $data
     * @return Form
     */
    public function fromArray(array $data): Form;

    /**
     * @return array<string, mixed> All input data
     */
    public function export(): array;
}
