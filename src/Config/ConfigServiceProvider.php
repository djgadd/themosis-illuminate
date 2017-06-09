<?php

namespace KeltieCochrane\Illuminate\Config;

use Illuminate\Config\Repository;
use Themosis\Foundation\ServiceProvider;

class ConfigServiceProvider extends ServiceProvider
{
  /**
   * {@inheritdoc}
   */
  public function register ()
  {
    $this->app->singleton('config', function($app) {
      return new Repository([]);
    });

    // Provide some compatibility with Themosis
    $this->app->alias('config', 'config.factory');

    // If we already have a finder available replace it, this is for users who
    // are just including the library as a provider in their providers config file
    if ($this->app->bound('config.finder')) {
      $paths = $this->app['config.finder']->getPaths();

      $this->app->singleton('config.finder', function ($app) use ($paths) {
        return (new ConfigFinder)->addPaths($paths);
      });
    }
    // Other wise create one, this is for users who are replacing the config service
    // in it's entirety, before themosis has loaded any service providers.
    else {
      $this->app->singleton('config.finder', function () {
        return new ConfigFinder();
      });
    }
  }

  /**
   * {@inheritdoc}
   */
  public function provides ()
  {
    return [
      'config',
      'config.factory',
      'config.finder',
    ];
  }
}
