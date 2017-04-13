<?php

namespace KeltieCochrane\Illuminate\Translation;

use Themosis\Facades\Facade;

/**
 * @see \Illuminate\Translation\Translator
 */
class LangFacade extends Facade
{
  /**
   * Get the registered name of the component.
   *
   * @return string
   */
  protected static function getFacadeAccessor()
  {
    return 'translator';
  }
}
