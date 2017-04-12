<?php

namespace KeltieCochrane\Illuminate\Translation;

use Illuminate\Translation\FileLoader;
use Illuminate\Translation\Translator;
use Themosis\Foundation\ServiceProvider;

class TranslationServiceProvider extends ServiceProvider
{
  /**
   * Indicates if loading of the provider is deferred.
   *
   * @var bool
   */
  protected $defer = true;

  /**
   * {@inheritdoc}
   */
  public function register()
  {
    $this->registerLoader();

    $this->app->singleton('translator', function ($app) {
      $loader = $app['translation.loader'];

      // When registering the translator component, we'll need to set the default
      // locale as well as the fallback locale. So, we'll grab the application
      // configuration so we can easily get both of these values from there.
      // $locale = $app['config']['theme.locale'];
      $locale = $app['config']['theme.locale'] ?: 'en';
      $trans = new Translator($loader, $locale);
      $trans->setFallback($app['config']['theme.locale'] ?: 'en');
      return $trans;
    });
  }

  /**
   * Register the translation line loader.
   *
   * @return void
   */
  protected function registerLoader()
  {
    $this->app->singleton('translation.loader', function ($app) {
      return new FileLoader($app['files'], themosis_path('base').DS.'translations');
    });
  }

  /**
   * {@inheritdoc}
   */
  public function provides()
  {
    return [
      'translator',
      'translation.loader'
    ];
  }
}
