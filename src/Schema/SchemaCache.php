<?php

namespace Poles\Json\Schema;

use const DIRECTORY_SEPARATOR;
use function file_put_contents;
use function str_replace;
use function var_export;

class SchemaCache
{
    /** @var string */
    private $cacheDir;

    public function __construct(string $cacheDir)
    {
        $this->cacheDir = $cacheDir;
    }

    public function load(string $className): ?Schema
    {
        $path = $this->getFilePath($className);
        $result = @include($path);
        if ($result instanceof Schema) {
            return $result;
        }
        return null;
    }

    public function write(string $className, Schema $schema): void
    {
        $exportedSchema = var_export($schema, true);

        $phpCode = <<<PHP
<?php

return {$exportedSchema};

PHP;
        file_put_contents($this->getFilePath($className), $phpCode);
    }

    private function getFilePath(string $className): string
    {
        $fileName = str_replace('\\', '.', trim($className, '\\')) . '.php';
        return $this->cacheDir . DIRECTORY_SEPARATOR . $fileName;
    }
}
