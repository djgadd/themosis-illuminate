<?php

namespace KeltieCochrane\Illuminate\Filesystem;

use Illuminate\Filesystem\Filesystem;
use Themosis\Foundation\ServiceProvider;
use Illuminate\Filesystem\FilesystemManager;

class FilesystemServiceProvider extends ServiceProvider
{
  /**
   * Register the service provider.
   *
   * @return void
   */
  public function register()
  {
    $this->registerNativeFilesystem();
    $this->registerFlysystem();
  }

  /**
   * Register the native filesystem implementation.
   *
   * @return void
   */
  protected function registerNativeFilesystem()
  {
    $this->app->singleton('files', function () {
      return new Filesystem;
    });
  }

  /**
   * Register the driver based filesystem.
   *
   * @return void
   */
  protected function registerFlysystem()
  {
    $this->registerManager();

    $this->app->singleton('filesystem.disk', function ($app) {
      return $app['filesystem']->disk($this->getDefaultDriver());
    });
  }

  /**
   * Register the filesystem manager.
   *
   * @return void
   */
  protected function registerManager()
  {
    $this->app->singleton('filesystem', function () {
      return new FilesystemManager($this->app);
    });
  }

  /**
   * Get the default file driver.
   *
   * @return string
   */
  protected function getDefaultDriver()
  {
    return $this->app['config']['filesystems.default'];
  }

  /**
   * {@inheritdoc}
   */
  public function provides()
  {
    return [
      'files',
      'filesystem',
      'filesystem.disk',
    ];
  }
}
