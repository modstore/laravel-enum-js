<?php

namespace Modstore\LaravelEnumJs\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Modstore\LaravelEnumJs\Resources\OutputFormatFactory;
use const PATHINFO_FILENAME;

class GenerateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'enum-js:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate javascript files from your php enum files.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Delete any existing generated files.
        $files = Storage::disk(config('laravel-enum-js.output_disk'))->allFiles();

        // Just to ensure this isn't accidentally the wrong directly with non-js files.
        $nonJsFiles = collect($files)->filter(function ($filename) {
            return preg_match('/\.' . config('laravel-enum-js.output_file_extension', 'js') . '$/', $filename) !== 1;
        });

        if ($nonJsFiles->count() > 0) {
            throw new \Exception('Js enums directory contains non-js files, please check your config.');
        }

        Storage::disk(config('laravel-enum-js.output_disk'))->delete($files);

        $pattern = '/' . collect(config('laravel-enum-js.namespaces'))->map(function ($item) {
                return str_replace('\\*', '.+', preg_quote($item));
            })->implode('|') . '/';

        $classLoader = require('vendor/autoload.php');
        $classes = array_unique(array_merge(get_declared_classes(), array_keys($classLoader->getClassMap())));

        // Create a js file for any class that matches the specified pattern.
        foreach ($classes as $class) {
            if (preg_match($pattern, $class) !== 1) {
                continue;
            }

            $this->writeFile($class);
        }

        return 0;
    }

    /**
     * Create a js file from the constants in the provided class.
     *
     * @param string $class
     * @throws \ReflectionException
     */
    protected function writeFile(string $class): void
    {
        $outputPath = $class;
        foreach (config('laravel-enum-js.output_transform') as $pattern => $replacement) {
            $outputPath = preg_replace('/' . preg_quote($pattern) . '/', $replacement, $outputPath);
        }
        $outputPath .= '.' . config('laravel-enum-js.output_file_extension', 'js');

        $reflection = new \ReflectionClass($class);

        $formatAsObject = config('laravel-enum-js.as_object', false);
        $formatter = OutputFormatFactory::create($formatAsObject ? 'object' : 'constant', $reflection);

        Storage::disk(config('laravel-enum-js.output_disk'))->put($outputPath, $formatter->getFileContents());

        $this->info(sprintf('File written to: %s', $outputPath));
    }

}
