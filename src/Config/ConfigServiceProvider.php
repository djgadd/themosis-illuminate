<?php

namespace KeltieCochrane\Illuminate\Config;

use Themosis\Foundation\ServiceProvider;

class ConfigServiceProvider extends ServiceProvider
{
  /**
   * {@inheritdoc}
   */
  public function register ()
  {
    $this->app->singleton('config', function($app) {
      return new Repository($app['config.finder']);
    });
  }

  /**
   * {@inheritdoc}
   */
  public function provides ()
  {
    return [
      'config',
    ];
  }
}
