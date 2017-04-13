<?php

namespace KeltieCochrane\Illuminate\Validation;

use Illuminate\Validation\Factory;
use Themosis\Foundation\ServiceProvider;
use Illuminate\Validation\DatabasePresenceVerifier;
use KeltieCochrane\Illuminate\Validation\ValidatorFacade;

class ValidationServiceProvider extends ServiceProvider
{
  /**
   * Indicates if loading of the provider is deferred.
   * @var  bool
   */
  protected $defer = true;

  /**
   * Add valid_nonce validation rule
   *
   * @return void
   */
  public function boot ()
  {
    ValidatorFacade::extend('valid_nonce', function ($attaribute, $value, $params, $validator) {
      $action = $params[0];
      return (bool) wp_verify_nonce($value, $action);
    });
  }

  /**
   * {@inheritdoc}
   */
  public function register ()
  {
    $this->registerPresenceVerifier();
    $this->registerValidationFactory();
  }

  /**
   * Register the validation factory.
   * @return  void
   */
  protected function registerValidationFactory ()
  {
    $this->app->singleton('validator', function ($app) {
      $validator = new Factory($app['translator'], $app);

      // The validation presence verifier is responsible for determining the existence of
      // values in a given data collection which is typically a relational database or
      // other persistent data stores. It is used to check for "uniqueness" as well.
      if (isset($app['db']) && isset($app['validation.presence'])) {
        $validator->setPresenceVerifier($app['validation.presence']);
      }

      return $validator;
    });
  }

  /**
   * Register the database presence verifier.
   * @return  void
   */
  protected function registerPresenceVerifier ()
  {
    $this->app->singleton('validation.presence', function ($app) {
      return new DatabasePresenceVerifier($app['db']);
    });
  }

  /**
   * {@inheritdoc}
   */
  public function provides ()
  {
    return [
      'validator',
      'validation.presence',
    ];
  }
}
