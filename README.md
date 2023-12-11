# Filament TineMce Editor

A [TineMce](https://www.tiny.cloud/) integration for [Filament](https://filamentphp.com/) Admin/Forms.

![tiny-editor](images/filament-tinyeditor.jpg?raw=true)

## Installation

Install the package via composer

```bash
composer require amidesfahani/filament-tinyeditor
```

Publish assets
```bash
php artisan vendor:publish --provider="AmidEsfahani\FilamentTinyEditor\TinyeditorServiceProvider"
php artisan vendor:publish --provider="AmidEsfahani\FilamentTinyEditor\TinyeditorServiceProvider" --tag="config"
php artisan vendor:publish --provider="AmidEsfahani\FilamentTinyEditor\TinyeditorServiceProvider" --tag="views"
php artisan vendor:publish --provider="AmidEsfahani\FilamentTinyEditor\TinyeditorServiceProvider" --tag="public"
```

## Usage

The editor extends the default Field class so most other methods available on that class can be used when adding it to a form.

```php
use AmidEsfahani\FilamentTinyEditor\TinyEditor;

TinyEditor::make('content')
	->fileAttachmentsDisk('public')
	->fileAttachmentsVisibility('public')
	->fileAttachmentsDirectory('uploads')
    ->profile('default|simple|full|minimal|none|custom')
	->rtl() // Set RTL or use ->direction('auto|rtl|ltr')
	->columnSpan('full')
    ->required();
```

## Config

The plugin will work without publishing the config, but should you need to change any of the default settings you can publish the config file with the following Artisan command:

```bash
php artisan vendor:publish --tag="filament-tinyeditor-config"
```


### Profiles / Tools

The package comes with 4 profiles (or toolbars) out of the box. You can also use a pipe `|` to separate tools into groups. The default profile is the full set of tools.

```php
'profiles' => [
    'default' => [
		'plugins' => 'accordion autoresize codesample directionality advlist link image lists preview pagebreak searchreplace wordcount code fullscreen insertdatetime media table emoticons',
		'toolbar' => 'undo redo removeformat | styles | bold italic | rtl ltr | alignjustify alignright aligncenter alignleft | numlist bullist outdent indent | forecolor backcolor | blockquote table toc hr | image link media codesample emoticons | wordcount fullscreen',
		'upload_directory' => null,
	],

	'simple' => [
		'plugins' => 'autoresize directionality emoticons link wordcount',
		'toolbar' => 'removeformat | bold italic | rtl ltr | numlist bullist | link emoticons',
		'upload_directory' => null,
	],

	'minimal' => [
		'plugins' => 'link wordcount',
		'toolbar' => 'bold italic link numlist bullist',
		'upload_directory' => null,
	],

	'full' => [
		'plugins' => 'accordion autoresize codesample directionality advlist autolink link image lists charmap preview anchor pagebreak searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media table emoticons template help',
		'toolbar' => 'undo redo removeformat | styles | bold italic | rtl ltr | alignjustify alignright aligncenter alignleft | numlist bullist outdent indent accordion | forecolor backcolor | blockquote table toc hr | image link anchor media codesample emoticons | visualblocks print preview wordcount fullscreen help',
		'upload_directory' => null,
	],
],
```

### RTL Support

In order for things like text align to work properly with RTL languages you 
can switch the `direction` key in the config to 'rtl'.

```php
// config/filament-tinyeditor.php
'direction' => 'rtl'
```

### Template Plugin Examples (external template list)

This is the contents your backend page should return if you specify a URL in the templates option. A simple array containing each template to present. This URL can be a backend page, for example a PHP file.

```json
[
  {"title": "Some title 1", "description": "Some desc 1", "content": "My content"},
  {"title": "Some title 2", "description": "Some desc 2", "content": "My content"}
]
```
```php
TinyEditor::make('contract')
	->columnSpan('full')
	->templates(route('my_template_route_name'))
    ->required();
```

## Versioning

This project follow the [Semantic Versioning](https://semver.org/) guidelines.

## License

Licensed under the MIT license, see [LICENSE.md](LICENSE.md) for details.