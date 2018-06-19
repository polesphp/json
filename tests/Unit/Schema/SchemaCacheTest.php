<?php

namespace Poles\Json\Tests\Unit\Schema;

use function file_exists;
use PHPUnit\Framework\TestCase;
use Poles\Json\Schema\SchemaCache;
use Poles\Json\Tests\Support\AllTypesClass;
use Poles\Json\Tests\Support\SchemaHelper;
use function sys_get_temp_dir;

class SchemaCacheTests extends TestCase
{
    /** @var string */
    private $dir;

    /** @var string */
    private $file;

    /** @var SchemaCache */
    private $cache;

    public function setUp()
    {
        $this->dir = sys_get_temp_dir();
        $this->file = $this->dir . '/Poles.Json.Tests.Support.AllTypesClass.php';
        if (file_exists($this->file)) {
            unlink($this->file);
        }
        $this->cache = new SchemaCache($this->dir);
    }

    public function testLoadNonExistent()
    {
        $this->assertNull($this->cache->load(AllTypesClass::class));
    }

    public function testWriteCreatesNewFile()
    {
        $this->cache->write(AllTypesClass::class, SchemaHelper::infer(AllTypesClass::class));
        $this->assertTrue(file_exists($this->file));
    }

    public function testWrittenFileYieldsSchema()
    {
        $schema = SchemaHelper::infer(AllTypesClass::class);
        $this->cache->write(AllTypesClass::class, $schema);

        $this->assertEquals($schema, include($this->file));
    }

    public function testLoadAfterWriteYieldsSameSchema()
    {
        $schema = SchemaHelper::infer(AllTypesClass::class);
        $this->cache->write(AllTypesClass::class, $schema);

        $this->assertEquals($schema, $this->cache->load(AllTypesClass::class));
    }
}
