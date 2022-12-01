<?php

namespace Modstore\LaravelEnumJs\Tests\Console\Commands;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use Modstore\LaravelEnumJs\Tests\TestCase;

class GenerateCommandTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Config::set('filesystems.disks.enum-js', [
            'driver' => 'local',
            'root' => sys_get_temp_dir() . '/laravel-enum-js/Output',
        ]);

        Config::set('laravel-enum-js.output_disk', 'enum-js');
        Config::set('laravel-enum-js.input_path', '../../../../../tests/resources/Enums');
    }

    public function testGenerate()
    {
        // Include some classes for this test.
        include('tests/resources/Enums/Status.php');
        include('tests/resources/Enums/StringValue.php');
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
            'StringValue.js',
            'Sub/Type.js',
        ], $generatedFiles);
    }

    public function generatedContentDataProvider(): array
    {
        return [
            'int' => [
                'filename' => 'Status.php',
                'expectedContent' => "export const Inactive = 0\nexport const Active = 1\n",
            ],
            'string' => [
                'filename' => 'StringValue.php',
                'expectedContent' => "export const Inactive = \"inactive\"\nexport const Active = \"active\"\n",
            ],
            'native' => [
                'filename' => 'Native/Base.php',
                'expectedContent' => "export const Value1 = \"Value1\"\nexport const Value2 = \"Value2\"\n",
            ],
            'native backed int' => [
                'filename' => 'Native/BackedInt.php',
                'expectedContent' => "export const Value1 = 1\nexport const Value2 = 2\n",
            ],
            'native backed string' => [
                'filename' => 'Native/BackedString.php',
                'expectedContent' => "export const Value1 = \"value-1\"\nexport const Value2 = \"value-2\"\n",
            ],
        ];
    }

    /**
     * @dataProvider generatedContentDataProvider
     */
    public function testGeneratedContent(string $filename, string $expectedContent)
    {
        include('tests/resources/Enums/' . $filename);

        Artisan::call('enum-js:generate');

        $jsFilename = preg_replace('/\.php$/', '.js', $filename);

        $generatedContent = Storage::disk(config('laravel-enum-js.output_disk'))->get($jsFilename);

        $this->assertSame($expectedContent, $generatedContent);
    }
}
