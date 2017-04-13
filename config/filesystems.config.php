<?php

return [

  /*
  |--------------------------------------------------------------------------
  | Default Filesystem Disk
  |--------------------------------------------------------------------------
  |
  | Here you may specify the default filesystem disk that should be used
  | by the framework. The "local" disk, as well as a variety of cloud
  | based disks are available to your application. Just store away!
  |
  */
  'default' => 'storage',

  /*
  |--------------------------------------------------------------------------
  | Filesystem Disks
  |--------------------------------------------------------------------------
  |
  | Here you may configure as many filesystem "disks" as you wish, and you
  | may even configure multiple disks of the same driver. Defaults have
  | been setup for each driver as an example of the required options.
  |
  | Supported Drivers: "local"
  |
  */
  'disks' => [
    'local' => [
      'driver' => 'local',
      'root' => themosis_path('storage'),
    ],

    // Your theme/ folder
    'theme' => [
      'driver' => 'local',
      'root' => themosis_path('theme'),
      'url' => str_replace('/dist', '/', themosis_theme_assets()),
      'visibility' => 'public',
    ],

    // Your theme/resources folder
    'resources' => [
      'driver' => 'local',
      'root' => themosis_path('theme.resources'),
      'url' => str_replace('/dist', '/resources/', themosis_theme_assets()),
      'visibility' => 'public',
    ],

    // Your theme/dist folder
    'dist' => [
      'driver' => 'local',
      'root' => themosis_path('theme').'dist',
      'url' => themosis_theme_assets(),
      'visibility' => 'public',
    ],
  ],
];
