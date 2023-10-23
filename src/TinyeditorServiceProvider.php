<?php

namespace AmidEsfahani\FilamentTinyEditor;

use Filament\Support\Assets\Js;
use Filament\Support\Assets\Css;
use Spatie\LaravelPackageTools\Package;
use Filament\Support\Facades\FilamentAsset;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class TinyeditorServiceProvider extends PackageServiceProvider
{
	public function configurePackage(Package $package): void
	{
		$package->name('filament-tinyeditor')->hasConfigFile()->hasViews();
	}

	public function packageBooted(): void
    {
        FilamentAsset::register([
			Css::make('tiny-css', __DIR__ . '/../resources/css/style.css'),
			Js::make('tinymce', 'https://cdn.jsdelivr.net/npm/tinymce@6.7.1/tinymce.js'),
			Js::make('tinymce-lang-fa', 'https://cdn.jsdelivr.net/npm/tinymce-i18n@23.10.9/langs6/fa.min.js')->loadedOnRequest(),
		], package: 'amidesfahani/filament-tinyeditor');
	}
}