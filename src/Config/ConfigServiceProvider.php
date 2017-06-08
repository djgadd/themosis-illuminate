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

    $this->app->singleton('config.finder', function () {
      return new ConfigFinder();
    });
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
