<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Model;
use App\Observers\AuditObserver;
use Illuminate\Support\Facades\File;

class AuditServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Merge configuration
        $this->mergeConfigFrom(
            __DIR__.'/../../config/audit.php', 'audit'
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Publish configuration
        $this->publishes([
            __DIR__.'/../../config/audit.php' => config_path('audit.php'),
        ], 'audit-config');

        // Only register observers if audit is enabled
        if (config('audit.enabled', true)) {
            $this->registerAuditObservers();
        }

        // Register console commands
        if ($this->app->runningInConsole()) {
            $this->commands([
                \App\Console\Commands\AuditCleanupCommand::class,
                \App\Console\Commands\AuditStatsCommand::class,
            ]);
        }
    }

    /**
     * Register audit observers for all models
     */
    protected function registerAuditObservers(): void
    {
        $excludedModels = config('audit.excluded_models', []);
        
        // Get all model files
        $modelPath = app_path('Models');
        
        if (File::exists($modelPath)) {
            $modelFiles = File::allFiles($modelPath);
            
            foreach ($modelFiles as $file) {
                $modelName = $file->getFilenameWithoutExtension();
                $modelClass = "App\\Models\\{$modelName}";
                
                // Skip if model doesn't exist or is excluded
                if (!class_exists($modelClass) || in_array($modelClass, $excludedModels)) {
                    continue;
                }
                
                // Check if it's a valid Eloquent model
                if (is_subclass_of($modelClass, Model::class)) {
                    try {
                        $modelClass::observe(AuditObserver::class);
                        
                        // Log successful registration in debug mode
                        if (config('app.debug')) {
                            logger()->debug("Audit observer registered for model: {$modelClass}");
                        }
                    } catch (\Exception $e) {
                        // Log error but don't break the application
                        logger()->error("Failed to register audit observer for {$modelClass}: " . $e->getMessage());
                    }
                }
            }
        }
        
        // Manually register specific models if auto-discovery fails
        $this->registerSpecificModels();
    }

    /**
     * Manually register observers for specific models
     */
    protected function registerSpecificModels(): void
    {
        $models = [
            \App\Models\User::class,
            // Add other important models here
        ];

        foreach ($models as $modelClass) {
            if (class_exists($modelClass)) {
                try {
                    $modelClass::observe(AuditObserver::class);
                } catch (\Exception $e) {
                    logger()->error("Failed to register audit observer for {$modelClass}: " . $e->getMessage());
                }
            }
        }
    }
}