<?php

namespace KeltieCochrane\Illuminate\Config;

use Themosis\Facades\Facade;

/**
 * @see \Illuminate\Config\Repository
 */
class ConfigFacade extends Facade
{
  /**
   * Get the registered name of the component.
   *
   * @return string
   */
  protected static function getFacadeAccessor()
  {
    return 'config';
  }
}
