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
        $package->name('filament-tinyeditor')->hasConfigFile()->hasViews()
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

    public function packageBooted(): void
    {
        $tiny_languages = [
            'tinymce-lang-ar' => 'https://cdn.jsdelivr.net/npm/tinymce-i18n@23.10.9/langs6/ar.min.js',
            'tinymce-lang-az' => 'https://cdn.jsdelivr.net/npm/tinymce-i18n@23.10.9/langs6/az.min.js',
            'tinymce-lang-bg_BG' => 'https://cdn.jsdelivr.net/npm/tinymce-i18n@23.10.9/langs6/bg_BG.min.js',
            'tinymce-lang-bn_BD' => 'https://cdn.jsdelivr.net/npm/tinymce-i18n@23.10.9/langs6/bn_BD.min.js',
            'tinymce-lang-ca' => 'https://cdn.jsdelivr.net/npm/tinymce-i18n@23.10.9/langs6/ca.min.js',
            'tinymce-lang-cs' => 'https://cdn.jsdelivr.net/npm/tinymce-i18n@23.10.9/langs6/cs.min.js',
            'tinymce-lang-cy' => 'https://cdn.jsdelivr.net/npm/tinymce-i18n@23.10.9/langs6/cy.min.js',
            'tinymce-lang-da' => 'https://cdn.jsdelivr.net/npm/tinymce-i18n@23.10.9/langs6/da.min.js',
            'tinymce-lang-de' => 'https://cdn.jsdelivr.net/npm/tinymce-i18n@23.10.9/langs6/de.min.js',
            'tinymce-lang-dv' => 'https://cdn.jsdelivr.net/npm/tinymce-i18n@23.10.9/langs6/dv.min.js',
            'tinymce-lang-el' => 'https://cdn.jsdelivr.net/npm/tinymce-i18n@23.10.9/langs6/el.min.js',
            'tinymce-lang-eo' => 'https://cdn.jsdelivr.net/npm/tinymce-i18n@23.10.9/langs6/eo.min.js',
            'tinymce-lang-es' => 'https://cdn.jsdelivr.net/npm/tinymce-i18n@23.10.9/langs6/es.min.js',
            'tinymce-lang-et' => 'https://cdn.jsdelivr.net/npm/tinymce-i18n@23.10.9/langs6/et.min.js',
            'tinymce-lang-es_MX' => 'https://cdn.jsdelivr.net/npm/tinymce-i18n@23.10.9/langs6/es_MX.min.js',
            'tinymce-lang-eu' => 'https://cdn.jsdelivr.net/npm/tinymce-i18n@23.10.9/langs6/eu.min.js',
            'tinymce-lang-fa' => 'https://cdn.jsdelivr.net/npm/tinymce-i18n@23.10.9/langs6/fa.min.js',
            'tinymce-lang-fi' => 'https://cdn.jsdelivr.net/npm/tinymce-i18n@23.10.9/langs6/fi.min.js',
            'tinymce-lang-fr_FR' => 'https://cdn.jsdelivr.net/npm/tinymce-i18n@23.10.9/langs6/fr_FR.min.js',
            'tinymce-lang-ga' => 'https://cdn.jsdelivr.net/npm/tinymce-i18n@23.10.9/langs6/ga.min.js',
            'tinymce-lang-gl' => 'https://cdn.jsdelivr.net/npm/tinymce-i18n@23.10.9/langs6/gl.min.js',
            'tinymce-lang-he_IL' => 'https://cdn.jsdelivr.net/npm/tinymce-i18n@23.10.9/langs6/he_IL.min.js',
            'tinymce-lang-hr' => 'https://cdn.jsdelivr.net/npm/tinymce-i18n@23.10.9/langs6/hr.min.js',
            'tinymce-lang-hu_HU' => 'https://cdn.jsdelivr.net/npm/tinymce-i18n@23.10.9/langs6/hu_HU.min.js',
            'tinymce-lang-hy' => 'https://cdn.jsdelivr.net/npm/tinymce-i18n@23.10.9/langs6/hy.min.js',
            'tinymce-lang-id' => 'https://cdn.jsdelivr.net/npm/tinymce-i18n@23.10.9/langs6/id.min.js',
            'tinymce-lang-is_IS' => 'https://cdn.jsdelivr.net/npm/tinymce-i18n@23.10.9/langs6/is_IS.min.js',
            'tinymce-lang-it' => 'https://cdn.jsdelivr.net/npm/tinymce-i18n@23.10.9/langs6/it.min.js',
            'tinymce-lang-ja' => 'https://cdn.jsdelivr.net/npm/tinymce-i18n@23.10.9/langs6/ja.min.js',
            'tinymce-lang-kab' => 'https://cdn.jsdelivr.net/npm/tinymce-i18n@23.10.9/langs6/kab.min.js',
            'tinymce-lang-kk' => 'https://cdn.jsdelivr.net/npm/tinymce-i18n@23.10.9/langs6/kk.min.js',
            'tinymce-lang-ko_KR' => 'https://cdn.jsdelivr.net/npm/tinymce-i18n@23.10.9/langs6/ko_KR.min.js',
            'tinymce-lang-ku' => 'https://cdn.jsdelivr.net/npm/tinymce-i18n@23.10.9/langs6/ku.min.js',
            'tinymce-lang-lt' => 'https://cdn.jsdelivr.net/npm/tinymce-i18n@23.10.9/langs6/lt.min.js',
            'tinymce-lang-lv' => 'https://cdn.jsdelivr.net/npm/tinymce-i18n@23.10.9/langs6/lv.min.js',
            'tinymce-lang-nb_NO' => 'https://cdn.jsdelivr.net/npm/tinymce-i18n@23.10.9/langs6/nb_NO.min.js',
            'tinymce-lang-nl' => 'https://cdn.jsdelivr.net/npm/tinymce-i18n@23.10.9/langs6/nl.min.js',
            'tinymce-lang-nl_BE' => 'https://cdn.jsdelivr.net/npm/tinymce-i18n@23.10.9/langs6/nl_BE.min.js',
            'tinymce-lang-oc' => 'https://cdn.jsdelivr.net/npm/tinymce-i18n@23.10.9/langs6/oc.min.js',
            'tinymce-lang-pl' => 'https://cdn.jsdelivr.net/npm/tinymce-i18n@23.10.9/langs6/pl.min.js',
            'tinymce-lang-pt_BR' => 'https://cdn.jsdelivr.net/npm/tinymce-i18n@23.10.9/langs6/pt_BR.min.js',
            'tinymce-lang-ro' => 'https://cdn.jsdelivr.net/npm/tinymce-i18n@23.10.9/langs6/ro.min.js',
            'tinymce-lang-ru' => 'https://cdn.jsdelivr.net/npm/tinymce-i18n@23.10.9/langs6/ru.min.js',
            'tinymce-lang-sk' => 'https://cdn.jsdelivr.net/npm/tinymce-i18n@23.10.9/langs6/sk.min.js',
            'tinymce-lang-sl_SI' => 'https://cdn.jsdelivr.net/npm/tinymce-i18n@23.10.9/langs6/sl_SI.min.js',
            'tinymce-lang-sq' => 'https://cdn.jsdelivr.net/npm/tinymce-i18n@23.10.9/langs6/sq.min.js',
            'tinymce-lang-sr' => 'https://cdn.jsdelivr.net/npm/tinymce-i18n@23.10.9/langs6/sr.min.js',
            'tinymce-lang-sv_SE' => 'https://cdn.jsdelivr.net/npm/tinymce-i18n@23.10.9/langs6/sv_SE.min.js',
            'tinymce-lang-ta' => 'https://cdn.jsdelivr.net/npm/tinymce-i18n@23.10.9/langs6/ta.min.js',
            'tinymce-lang-tg' => 'https://cdn.jsdelivr.net/npm/tinymce-i18n@23.10.9/langs6/tg.min.js',
            'tinymce-lang-th_TH' => 'https://cdn.jsdelivr.net/npm/tinymce-i18n@23.10.9/langs6/th_TH.min.js',
            'tinymce-lang-tr' => 'https://cdn.jsdelivr.net/npm/tinymce-i18n@23.10.9/langs6/tr.min.js',
            'tinymce-lang-ug' => 'https://cdn.jsdelivr.net/npm/tinymce-i18n@23.10.9/langs6/ug.min.js',
            'tinymce-lang-uk' => 'https://cdn.jsdelivr.net/npm/tinymce-i18n@23.10.9/langs6/uk.min.js',
            'tinymce-lang-vi' => 'https://cdn.jsdelivr.net/npm/tinymce-i18n@23.10.9/langs6/vi.min.js',
        ];

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

        $mainJs = 'https://cdn.jsdelivr.net/npm/tinymce@6.7.1/tinymce.js';
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
