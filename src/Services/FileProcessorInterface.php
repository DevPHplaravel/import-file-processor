<?php
namespace AlessandroMineo\ImportFileProcessor\Services;

interface FileProcessorInterface
{
    public function process(string $filePath): void;
}
