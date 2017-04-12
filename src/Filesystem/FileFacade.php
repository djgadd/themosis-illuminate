<?php

namespace KeltieCochrane\Illuminate\Filesystem;

use Themosis\Facades\Facade;

/**
 * @see \Illuminate\Filesystem\Filesystem
 */
class FileFacade extends Facade
{
  /**
   * Get the registered name of the component.
   *
   * @return string
   */
  protected static function getFacadeAccessor()
  {
    return 'files';
  }
}
