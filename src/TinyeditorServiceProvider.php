<?php

namespace AmidEsfahani\FilamentTinyEditor;

use Filament\Support\Assets\AlpineComponent;
use Filament\Support\Assets\Css;
use Filament\Support\Assets\Js;
use Filament\Support\Facades\FilamentAsset;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class TinyeditorServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('filament-tinyeditor')->hasConfigFile()->hasViews()->hasAssets()
            ->hasInstallCommand(
                function (InstallCommand $command) {
                    $command->publishConfigFile()->copyAndRegisterServiceProviderInApp()->askToStarRepoOnGitHub($this->getAssetPackageName());
                }
            );

        if (file_exists(__DIR__ . '/../../../vendor/tinymce/tinymce')) {
            $this->publishes([__DIR__ . '/../../../vendor/tinymce/tinymce' => public_path('vendor/tinymce')], 'public');
        }
        else if (file_exists(base_path('vendor/tinymce/tinymce'))) {
            $this->publishes([base_path('vendor/tinymce/tinymce') => public_path('vendor/tinymce')], 'public');
        }
    }

    public function packageRegistered(): void
    {
        $tinyVersion = config('filament-tinyeditor.version.tiny', '7.3.0');

        $tiny_licence_key = config('filament-tinyeditor.version.licence_key', 'no-api-key');

        $tiny_languages = Tiny::getLanguages();

        $languages = [];
        $optional_languages = config('filament-tinyeditor.languages', []);
        if (! is_array($optional_languages)) {
            $optional_languages = [];
        }

        foreach ($tiny_languages as $locale => $language) {
            $locale = str_replace('tinymce-lang-', '', $locale);
            $languages[] = Js::make(
                'tinymce-lang-' . $locale,
                array_key_exists($locale, $optional_languages) ? $optional_languages[$locale] : $language
            )->loadedOnRequest();
        }

        $provider = config('filament-tinyeditor.provider', 'cloud');

        $mainJs = 'https://cdn.jsdelivr.net/npm/tinymce@'.$tinyVersion.'/tinymce.js';

        if ($tiny_licence_key != 'no-api-key') {
            $mainJs = 'https://cdn.tiny.cloud/1/'.$tiny_licence_key.'/tinymce/'.$tinyVersion.'/tinymce.min.js';
        }

        if ($provider == 'vendor') {
            $mainJs = asset('vendor/tinymce/tinymce.min.js');
        }

        FilamentAsset::register([
            Css::make('tiny-css', __DIR__ . '/../resources/css/style.css'),
            Js::make('tinymce', $mainJs),
            AlpineComponent::make('tinyeditor', __DIR__ . '/../resources/dist/filament-tinymce-editor.js'),
            ...$languages,
        ], package: $this->getAssetPackageName());
    }

    protected function getAssetPackageName(): ?string
    {
        return 'amidesfahani/filament-tinyeditor';
    }
}
