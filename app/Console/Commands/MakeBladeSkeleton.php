<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeBladeSkeleton extends Command
{
    protected $signature = 'make:blade {name}';
    protected $description = 'Generate a Blade file with basic backend layout structure';

    public function handle()
{
    $name = str_replace('.', '/', strtolower($this->argument('name')));
    $viewPath = resource_path("views/backend/{$name}.blade.php");

    // Ensure the directory exists
    $directory = dirname($viewPath);
    if (!File::exists($directory)) {
        File::makeDirectory($directory, 0755, true, true);
    }

    if (File::exists($viewPath)) {
        $this->error("File already exists: {$viewPath}");
        return;
    }

    $content = <<<BLADE
@extends('backend.layouts.master')

@section('content')
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">

            {{-- Content goes here --}}

        </div>
    </div>
</div>
@endsection
BLADE;

    File::put($viewPath, $content);

    $this->info("Blade file created at: resources/views/backend/{$name}.blade.php");
}

}
