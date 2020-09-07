<?php

namespace Modstore\LaravelEnumJs\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

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
            return preg_match('/\.js$/', $filename) !== 1;
        });

        if ($nonJsFiles->count() > 0) {
            throw new \Exception('Js enums directory contains non-js files, please check your config.');
        }

        Storage::disk(config('laravel-enum-js.output_disk'))->delete($files);

        $pattern = '/' . collect(config('laravel-enum-js.namespaces'))->map(function ($item) {
            return str_replace('\\*', '.+', preg_quote($item));
        })->implode('|') . '/';

        $classes = get_declared_classes();

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
    protected function writeFile(string $class)
    {
        $outputPath = $class;
        foreach (config('laravel-enum-js.output_transform') as $pattern => $replacement) {
            $outputPath = preg_replace('/' . preg_quote($pattern) . '/', $replacement, $outputPath);
        }
        $outputPath .= '.js';

        $reflection = new \ReflectionClass($class);

        $outputString = '';
        foreach ($reflection->getConstants() as $key => $value) {
            $outputString .= sprintf("export const %s = %s\n", $key, $value);
        }

        Storage::disk(config('laravel-enum-js.output_disk'))->put($outputPath, $outputString);

        $this->info(sprintf('File written to: %s', $outputPath));
    }
}
