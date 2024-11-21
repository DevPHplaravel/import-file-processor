<?php

namespace DevPHPLaravel\ImportFileProcessor\Services;

interface FileProcessorInterface
{
    public function process(string $filePath): void;
}
