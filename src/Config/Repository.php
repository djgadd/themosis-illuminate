<?php

namespace KeltieCochrane\Illuminate\Config;

use Closure;
use Themosis\Config\ConfigFinder;
use Symfony\Component\Finder\Finder;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Config\Repository as IlluminateRepository;

class Repository extends IlluminateRepository
{
  /**
   * The file extensions. The order is important, check __construct to see why.
   *
   * @var array
   */
  protected $extensions = [
    'config.php',
    'php'
  ];

  /**
   * Create a new configuration repository. We have to handle the way Themosis
   * does it's config files here.
   *
   * @param \Themosis\Config\ConfigFinder $configFinder
   * @return void
   */
  public function __construct(ConfigFinder $configFinder)
  {
    // Call the parent constructor
    parent::__construct([]);

    $files = [];

    // Iterate over the extensions
    foreach ($this->extensions as $extension) {
      // Iterate over the config paths
      foreach ($configFinder->getPaths() as $configPath) {
        // Iterate over the files in $configPath
        foreach (Finder::create()->files()->name("*.{$extension}")->in($configPath) as $file) {
          // Generate dots if it's a nested file
          if ($nested = trim(str_replace($configPath, '', $file->getPath()), DIRECTORY_SEPARATOR)) {
              $nested = str_replace(DIRECTORY_SEPARATOR, '.', $nested).'.';
          }

          // Check to see if we've already saw this file. This is why it's
          // important to order $this->extensions, otherwise if 'php' was first
          // we'd have 'file.config' as the key in $files
          if (!in_array($file->getRealPath(), $files)) {
            $files[$nested.basename($file->getRealPath(), ".{$extension}")] = $file->getRealPath();

            // Add the config file to the repository
            $this->set($nested.basename($file->getRealPath(), ".{$extension}"), require $file->getRealPath());
          }
        }
      }
    }
  }
}
