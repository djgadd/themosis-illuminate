# Themosis Illuminate
A package for the Themosis framework that implements various Illuminate packages, some replace the Themosis packages (such as config and validator.) Also loads in most of Laravel's helper functions (see below.)

## Install
Install through composer: -
`composer require keltiecochrane/themosis-illuminate`

See below for how to activate the individual packages.

## Packages
The following packages have been implemented: -

* [illuminate/config](https://github.com/illuminate/config)
* [illuminate/filesystem](https://github.com/illuminate/filesystem)
* [illuminate/mail](https://github.com/illuminate/mail)
* [illuminate/translation](https://github.com/illuminate/translation)
* [illuminate/validation](https://github.com/illuminate/validation)

### Config
To use the config (which we recommend as it implements ArrayAccess which is required by most of the package implementations here) you'll need to replace Themosis' Service Provider and Facade. It's API compatible but implements additional features (it seems likely Themosis will eventually move to the Illuminate package [in the future too](https://github.com/themosis/framework/issues/372).)

#### Activation
Add the service provider to your `theme/resources/config/providers.config.php`: -
`KeltieCochrane\Illuminate\Config\ConfigServiceProvider::class`,

Replace the facade in your `theme/resources/config/theme.config.php`: -
`'Config' => KeltieCochrane\Illuminate\Config\ConfigFacade::class,`

Note: You don't necassarily have to add the facade at this moment, as it effectively does the same thing that the Themosis facade, but it's worth adding in case we need to make any changes in line with the Illuminate facade in the future.

#### Examples
Use at you normally would, e.g.: -
```
  // Normal usage
  Config::get('something');

  // Setting a default
  Config::get('theme.locale', 'en-GB');

  // Using ArrayAccess
  app('config')['theme.locale'];
```

### Filesystem
The filesystem is required by Translation and Validation, but is a useful feature to have anyway.

#### Activation
Copy the `filesystem.config.php` file to your `theme/resources/config` folder, some defaults have been setup for you.

Add the service provider to your `theme/resources/config/providers.config.php`: -
`KeltieCochrane\Illuminate\Filesystem\FilesystemServiceProvider::class,`

Optionally add the facades in your `theme/resources/config/theme.config.php`: -
```
  'File' => KeltieCochrane\Illuminate\Filesystem\FileFacade::class,
  'Storage' => KeltieCochrane\Illuminate\Filesystem\StorageFacade::class,
```

#### Examples
```
  // Get an array of files in the current directory
  File::files(__DIR__);

  // Make a file in the current directory
  File::put(__DIR__.DS.'file.txt', 'Contents');

  // Put a file in your 'storage' directory
  Storage::disk('local')->put('file.txt', 'Contents');

  // Check if a file exists in your theme's 'dist' directory
  Storage::disk('dist')->exists('css/app.css');

  // Get a file url in your theme's 'dist' directory
  Storage::disk('dist')->url('css/app.css');
```

See the [Laravel docs](https://laravel.com/docs/5.4/filesystem) for more info.

### Mail
Mail is optional and not required by anything else, but is a nicer way of sending emails than through WordPress. A wp_mail Transport is provided and is used by default, so if your WordPress instance can send mail so can the Mail service.

#### Activation
Copy the `mail.config.php` file to your `theme/resources/config` folder, configure as appropriate.

If you're going to be using an external service such as MailGun or SparkPost you'll need to add the following to your `theme/resources/services.config.php` (you may need to create this file if you haven't already got one): -
```
  'mailgun' => [
      'domain' => env('MAILGUN_DOMAIN'),
      'secret' => env('MAILGUN_SECRET'),
  ],

  'ses' => [
      'key' => env('SES_KEY'),
      'secret' => env('SES_SECRET'),
      'region' => 'us-east-1',
  ],

  'sparkpost' => [
      'secret' => env('SPARKPOST_SECRET'),
  ],
```

Add the service provider to your `theme/resources/config/providers.config.php`: -
`KeltieCochrane\Illuminate\Mail\MailServiceProvider::class,`

Optionally add the facades in your `theme/resources/config/theme.config.php`: -
`'Mail' => KeltieCochrane\Illuminate\Mail\MailFacade::class,`

#### Examples
```
  // Send an email
  Mail::send('mail.welcome', ['with' => 'Some data for the view.'], function ($mailer) {
    $attachmentPath = Storage::disk('dist')->getDriver()->getAdapter()->getPathPrefix().'images/call-icon.svg';

    $mailer->to('someone@somecompany.tld')
      ->bcc('mailbin@somecompany.tld')
      ->subject('Subject')
      ->attach($attachmentPath);
  });
```

See the [Laravel docs](https://laravel.com/docs/5.4/mail) for more info.

### Translation
Translation requires Filesystem to be loaded in and is required by Validation.

#### Activation
Add the service provider to your `theme/resources/config/providers.config.php`: -
`KeltieCochrane\Illuminate\Translation\TranslationServiceProvider::class,`

Optionally add the facades in your `theme/resources/config/theme.config.php`: -
`'Lang' => KeltieCochrane\Illuminate\Translation\LangFacade::class,`

#### Files
You can add translations from the [Laravel](https://github.com/laravel/laravel/tree/master/resources/lang), you'll need to add them to your `theme/resources/lang` folder, the only one worth copying for now is the validation file (if indeed you're using validation.) You can of course create your own files to add translation throughout your site, but we'd still recommend using a translation plugin.

#### Examples
```
  // Is there a translation available in 'file' under 'key'
  Lang::has('file.key');

  // Is there a translation under the 'it' locale for 'file' under 'key'
  // Refuse to use the fall back locale
  Lang::has('file.key', 'it', false);

  // Get the translation
  Lang::trans('file.key');

  // Get the translation and replace some text in it
  Lang::trans('file.key', ['name' => 'John']);

  // Get the 'it' translation and replace some text in it
  // Refuse to use the fall back locale
  Lang::trans('file.key', ['name' => 'John'], 'it', false);
```

See the [Laravel docs](https://laravel.com/docs/5.4/localization) for more info.

### Validation
Validation requires Translation and Filesystem. It replaces the built in Themosis Validator (if you use the facade) which is more of a sanatiser than a validator.

#### Activation
Add the service provider to your `theme/resources/config/providers.config.php`: -
`KeltieCochrane\Illuminate\Validation\ValidationServiceProvider::class,`

Add the facades in your `theme/resources/config/theme.config.php`: -
`'Lang' => KeltieCochrane\Illuminate\Validation\ValidatorFacade::class,`

#### Examples
```
  $validator = Validator::make($request->all(), [
    'title' => 'required|unique:posts|max:255',
    'body' => 'required',
  ]);

  if ($validator->fails()) {
    return view()->with([
	  'errors' => $validator->errors(),
    ]);
  }
```

See the [Laravel docs](https://laravel.com/docs/5.4/validation#manually-creating-validators) for more info.

## Helpers
The following (additional) helpers are available: -

* array_add
* array_collapse
* array_divide
* array_dot
* array_first
* array_flatten
* array_forget
* array_has
* array_last
* array_only
* array_pluck
* array_prepend
* array_pull
* array_sort
* array_sort_recursive
* array_where
* array_wrap
* camel_case
* class_basename
* class_uses_recursive
* collect
* config
* data_fill
* data_get
* data_set
* ends_with
* env
* head
* kebab_case
* last
* object_get
* preg_replace_array
* retry
* request
* snake_case
* str_finish
* str_limit
* str_plural
* str_random
* str_replace_array
* str_replace_first
* str_replace_last
* str_singular
* str_slug
* studly_case
* tap
* title_case
* trait_uses_recursive
* trans
* trans_choice
* validator
* windows_os

See the [Laravel docs](https://laravel.com/docs/5.4/helpers) for more info.

## Todo
* Tests - a lot of this is trusting in the Laravel packages and Themosis behaving themselves
* Add more packages
* Possibly merge Cache and Logger into this so there's one package to rule them all

## Support
This package is provided as is, though we'll endeavour to help where we can.

## Contributing
Any contributions would be encouraged and much appreciated, you can contribute by: -

* Reporting bugs
* Suggesting features
* Sending pull requests
