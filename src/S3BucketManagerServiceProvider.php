<?php
/**
 * @package RocksCoder\S3BucketManager
 * @author Md Siddiqur Rahman <siddikcoder@gmail.com>
 * @copyright 2024 RocksCoder
 * @license MIT
 * @link https://github.com/siddik-web/s3-bucket-manager
 * @version 1.0.0
 * @since 1.0.0
 */

namespace RocksCoder\S3BucketManager;

use Illuminate\Support\ServiceProvider;

/**
 * S3BucketManagerServiceProvider class
 * 
 * @since 1.0.0
 */
class S3BucketManagerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     * 
     * @since 1.0.0
     */
    public function boot()
    {
        // Optionally, you can publish package configuration files
        $this->publishes([
            __DIR__ . '/config/s3-bucket-manager.php' => config_path('s3-bucket-manager.php'),
        ], 'config');
    }

    /**
     * Register the application services.
     * 
     * @since 1.0.0
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/config/s3-bucket-manager.php', 's3-bucket-manager');

        $this->app->bind('s3-bucket-manager', function ($app) {
            $config = $app->make('config')->get('s3-bucket-manager');
            return new S3BucketManager(
                $config['aws_access_key_id'],
                $config['aws_secret_access_key'],
                $config['aws_region'],
                $config['bucket_name']
            );
        });
    }
}