<?php

namespace Com\KeltieCochrane\Illuminate\Facades;

use Themosis\Facades\Facade;

class Validator extends Facade
{
  /**
   * Get the registered name of the component.
   *
   * @return string
   */
  protected static function getFacadeAccessor ()
  {
    return 'validator';
  }
}
