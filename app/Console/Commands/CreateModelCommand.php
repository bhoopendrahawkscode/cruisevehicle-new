<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Pluralizer;

class CreateModelCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    const STUB_FILE = __DIR__ . '/../../../stubs/MainTranslationModel.stub';
    protected $signature = 'create:model {name} {trans_attr}';

    protected $files;
    public function __construct(Filesystem $files)
    {
        parent::__construct();
        $this->files = $files;
    }

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Using by this command we can create main model of translation models';

    /**
     * Execute the console command.
     */
    protected function getSingularClassName($name)
    {
        return Pluralizer::singular($name);
    }
    protected function getPluralClassName($name)
    {
        return Pluralizer::plural($name);
    }


    protected function getSourceFilePath()
    {
        $file_name = $this->getSingularClassName($this->argument('name')) . '.php';
        return base_path("App\\Models\\$file_name");
    }


    protected function  getStubContents(string $stub, array $stubVar = [])
    {
        $contents = file_get_contents($stub);
        foreach ($stubVar as $search => $replace) {
            $contents = str_replace('$' . $search . '$', $replace, $contents);
        }

        return $contents;
    }

    protected function  makeDir(string $path)
    {
        if (!$this->files->isDirectory($path)) {
            $this->files->makeDirectory($path, 0777, true, true);
        }

        return $path;
    }

    
    protected function getSourceFile()
    {
        return $this->getStubContents(self::STUB_FILE, $this->getStubVariables());
    
    }

    protected function getStubVariables()
    {
        $name = $this->getSingularClassName($this->argument('name'));
        $model_name = ucwords($name);
        return [
            'CLASS_NAME'        => $model_name,
            'TABLE_NAME' => strtolower($this->getPluralClassName($this->argument('name'))),
            'TRANSLATION_ATTRIBUTES' => $this->argument('trans_attr'),
            'TRANSLATION_MODEL_NAME' => $model_name . 'Translation',
            'MODEL_NAME_IN_SMALL' => strtolower($model_name),
            'FOREIGN_KEY_NAME' => strtolower($model_name) . '_id',
        ];
    }



    public function handle()
    {
        $path = $this->getSourceFilePath();
        $this->makeDir(dirname($path));
        $contents = $this->getSourceFile();
        if (!$this->files->exists($path)) {
            $this->files->put($path, $contents);
            $this->info("File : {$path} created");
        } else {
            $this->warn("File : {$path} already exits");
        }
    }
}
