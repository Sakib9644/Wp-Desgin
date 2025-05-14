<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeBladeSkeleton extends Command
{
protected $signature = 'make:blade {name} {routeType=web}';
    protected $description = 'Generate a Blade file with basic backend layout structure';

   public function handle()
{
    $name = strtolower($this->argument('name')); // e.g., product
    $routeType = strtolower($this->argument('routeType') ?? 'web'); // web or admin
    $className = ucfirst($name); // Product
    $controllerName = $className . 'Controller';
    $modelName = $className;

    // 1. Generate Model and Migration
    if (!class_exists("$modelName")) {
        $this->call('make:model', [
            'name' => "{$modelName}",
            '--migration' => true,
        ]);
        $this->info("Model and migration created: {$modelName}");
    } else {
        $this->error("Model already exists: {$modelName}");
    }

    // 2. Create Blade Views
    $views = ['index', 'create', 'edit'];
    foreach ($views as $view) {
        $viewPath = resource_path("views/backend/{$name}/{$view}.blade.php");
        $directory = dirname($viewPath);
        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0755, true, true);
        }

        if (!File::exists($viewPath)) {
            $content = <<<BLADE
@extends('backend.layouts.master')

@section('content')
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">

            {{-- {$view} page content for {$name} --}}

        </div>
    </div>
</div>
@endsection
BLADE;
            File::put($viewPath, $content);
            $this->info("View created: resources/views/backend/{$name}/{$view}.blade.php");
        } else {
            $this->error("View already exists: {$viewPath}");
        }
    }

    // 3. Create Controller
    $controllerPath = app_path("/Backend/{$controllerName}.php");
    if (!File::exists($controllerPath)) {
        $this->call('make:controller', [
            'name' => "/Backend/{$controllerName}",
            '--resource' => true
        ]);
        $this->info("Controller created: {$controllerName}");
    } else {
        $this->error("Controller already exists: {$controllerPath}");
    }

    // 4. Add Route
    $routeFile = base_path("routes/{$routeType}.php");
    if (!File::exists($routeFile)) {
        $this->error("Route file does not exist: routes/{$routeType}.php");
        return;
    }

    $routeEntry = "Route::resource('" . $name . "', App\Http\Controllers\Backend\\{$controllerName}::class);";

    $routeContent = File::get($routeFile);
    if (!str_contains($routeContent, $routeEntry)) {
        File::append($routeFile, "\n" . $routeEntry);
        $this->info("Route added to {$routeType}.php");
    } else {
        $this->error("Route already exists in {$routeType}.php");
    }
}

}
