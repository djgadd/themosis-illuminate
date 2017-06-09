<?php

namespace KeltieCochrane\Illuminate\Config;

use Symfony\Component\Finder\Finder;
use Themosis\Config\ConfigFinder as ThemosisConfigFinder;

class ConfigFinder extends ThemosisConfigFinder
{
    /**
     * The file extensions.
     *
     * @var array
     */
    protected $extensions = [
      'php',
      'config.php',
    ];

    /**
     * When a path is registered any matching files are read in and added to the
     * config Repository. Calls parent::addPath once finished
     *
     * @param string $key  The file URL if defined or numeric index.
     * @param string $path
     * @return $this
     */
    protected function addPath($key, $path)
    {
        // Iterate through the extensions looking for files in the path
        foreach ($this->extensions as $extension) {
          // Find all matching files for this extension
          foreach (Finder::create()->files()->name("*.{$extension}")->in($path) as $file) {
            // Skip if the file belongs to a more specific extension
            if ($this->fileMatchesMoreSpecificExtension(basename($file->getRealPath()), $extension)) {
              continue;
            }

            // Work out the files key
            $key = $this->getNestedDirectoryDots($path, $file->getPath()).basename($file->getRealPath(), ".{$extension}");

            // We already have a matching file so we need to merge the config
            if (array_key_exists($key, $this->files)) {
              $this->files[$key][] = $file->getRealPath();
              $items = $this->mergeConfigs(app('config')->get($key), require $file->getRealPath());
            }
            // Otherwise we can just require the config in
            else {
              $this->files[$key] = [$file->getRealPath()];
              $items = require $file->getRealPath();
            }

            app('config')->set($key, $items);
          }
        }

        return parent::addPath($key, $path);
    }

    /**
     * Determines if a file matches another more specific extension
     *
     * @param string $filename
     * @param string $extension
     * @return bool
     */
    protected function fileMatchesMoreSpecificExtension(string $filename, string $extension) : bool
    {
      return array_reduce($this->extensions, function ($match, $compare) use ($filename, $extension) {
        if ($match || $compare === $extension || strlen($compare) < strlen($extension)) {
          return $match;
        }

        return $filename === basename($filename, ".{$compare}").".{$compare}";
      }, false);
    }

    /**
     * Generates dots for files in $filePath nested in $path
     *
     * @param string $path
     * @param string $filePath
     * @return string
     */
    protected function getNestedDirectoryDots(string $path, string $filePath) : string
    {
      // Generate dots if it's a nested file
      if ($nested = trim(str_replace($path, '', $filePath), DIRECTORY_SEPARATOR)) {
          $nested = str_replace(DIRECTORY_SEPARATOR, '.', $nested).'.';
      }

      return $nested;
    }

    /**
     * Recusively merges configs together
     *
     * @param array $old
     * @param array $new
     * @return array
     */
    protected function mergeConfigs(array $old, array $new) : array
    {
      foreach ($new as $key => $val) {
        if (!array_key_exists($key, $old) || !is_array($old[$key]) || !is_array($val)) {
          $old[$key] = $val;
          continue;
        }

        if (is_int($key)) {
          $old[] = $val;
          continue;
        }

        $old[$key] = $this->mergeConfigs($old[$key], $val);
      }

      return $old;
    }
}
