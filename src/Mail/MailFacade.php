<?php

namespace KeltieCochrane\Illuminate\Mail;

use Themosis\Facades\Facade;
use Illuminate\Support\Testing\Fakes\MailFake;

/**
 * @see \Illuminate\Mail\Mailer
 */
class MailFacade extends Facade
{
  /**
   * Defer loading unless we need it, saves us a little bit of overhead if the
   * current request isn't trying to log anything.
   *
   * @var bool
   */
  protected $defer = true;

  /**
   * Replace the bound instance with a fake.
   *
   * @return void
   */
  public static function fake()
  {
    static::swap(new MailFake);
  }

  /**
   * Get the registered name of the component.
   *
   * @return string
   */
  protected static function getFacadeAccessor()
  {
    return 'mailer';
  }
}
