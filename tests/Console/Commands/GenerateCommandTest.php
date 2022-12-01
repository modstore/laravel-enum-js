<?php

namespace Modstore\LaravelEnumJs\Tests\Console\Commands;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use Modstore\LaravelEnumJs\Tests\TestCase;

class GenerateCommandTest extends TestCase
{
    public function testGenerate()
    {
        Config::set('filesystems.disks.enum-js', [
            'driver' => 'local',
            'root' => sys_get_temp_dir() . '/laravel-enum-js/Output',
        ]);

        Config::set('laravel-enum-js.output_disk', 'enum-js');
        Config::set('laravel-enum-js.input_path', '../../../../../tests/resources/Enums');

        // Include a couple of classes for this test.
        include('tests/resources/Enums/Status.php');
        include('tests/resources/Enums/Sub/Type.php');
        include('tests/resources/Enums/Native/BackedString.php');
        include('tests/resources/Enums/Native/Base.php');
        include('tests/resources/Enums/Native/BackedInt.php');
        include('tests/resources/Enums/ArrayType.php');

        Artisan::call('enum-js:generate');

        $generatedFiles = Storage::disk(config('laravel-enum-js.output_disk'))->allFiles();

        $this->assertSame([
            'ArrayType.js',
            'Native/BackedInt.js',
            'Native/BackedString.js',
            'Native/Base.js',
            'Status.js',
            'Sub/Type.js',
        ], $generatedFiles);
    }
}
