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
			Js::make('tinymce-lang-ar', 'https://cdn.jsdelivr.net/npm/tinymce-i18n@23.10.9/langs6/ar.min.js')->loadedOnRequest(),
            Js::make('tinymce-lang-az', 'https://cdn.jsdelivr.net/npm/tinymce-i18n@23.10.9/langs6/az.min.js')->loadedOnRequest(),
            Js::make('tinymce-lang-bg_BG', 'https://cdn.jsdelivr.net/npm/tinymce-i18n@23.10.9/langs6/bg_BG.min.js')->loadedOnRequest(),
            Js::make('tinymce-lang-bn_BD', 'https://cdn.jsdelivr.net/npm/tinymce-i18n@23.10.9/langs6/bn_BD.min.js')->loadedOnRequest(),
            Js::make('tinymce-lang-ca', 'https://cdn.jsdelivr.net/npm/tinymce-i18n@23.10.9/langs6/ca.min.js')->loadedOnRequest(),
            Js::make('tinymce-lang-cs', 'https://cdn.jsdelivr.net/npm/tinymce-i18n@23.10.9/langs6/cs.min.js')->loadedOnRequest(),
            Js::make('tinymce-lang-cy', 'https://cdn.jsdelivr.net/npm/tinymce-i18n@23.10.9/langs6/cy.min.js')->loadedOnRequest(),
            Js::make('tinymce-lang-da', 'https://cdn.jsdelivr.net/npm/tinymce-i18n@23.10.9/langs6/da.min.js')->loadedOnRequest(),
            Js::make('tinymce-lang-de', 'https://cdn.jsdelivr.net/npm/tinymce-i18n@23.10.9/langs6/de.min.js')->loadedOnRequest(),
            Js::make('tinymce-lang-dv', 'https://cdn.jsdelivr.net/npm/tinymce-i18n@23.10.9/langs6/dv.min.js')->loadedOnRequest(),
            Js::make('tinymce-lang-el', 'https://cdn.jsdelivr.net/npm/tinymce-i18n@23.10.9/langs6/el.min.js')->loadedOnRequest(),
            Js::make('tinymce-lang-eo', 'https://cdn.jsdelivr.net/npm/tinymce-i18n@23.10.9/langs6/eo.min.js')->loadedOnRequest(),
            Js::make('tinymce-lang-es', 'https://cdn.jsdelivr.net/npm/tinymce-i18n@23.10.9/langs6/es.min.js')->loadedOnRequest(),
            Js::make('tinymce-lang-et', 'https://cdn.jsdelivr.net/npm/tinymce-i18n@23.10.9/langs6/et.min.js')->loadedOnRequest(),
            Js::make('tinymce-lang-es_MX', 'https://cdn.jsdelivr.net/npm/tinymce-i18n@23.10.9/langs6/es_MX.min.js')->loadedOnRequest(),
            Js::make('tinymce-lang-eu', 'https://cdn.jsdelivr.net/npm/tinymce-i18n@23.10.9/langs6/eu.min.js')->loadedOnRequest(),
            Js::make('tinymce-lang-fa', 'https://cdn.jsdelivr.net/npm/tinymce-i18n@23.10.9/langs6/fa.min.js')->loadedOnRequest(),
            Js::make('tinymce-lang-fi', 'https://cdn.jsdelivr.net/npm/tinymce-i18n@23.10.9/langs6/fi.min.js')->loadedOnRequest(),
            Js::make('tinymce-lang-fr_FR', 'https://cdn.jsdelivr.net/npm/tinymce-i18n@23.10.9/langs6/fr_FR.min.js')->loadedOnRequest(),
            Js::make('tinymce-lang-ga', 'https://cdn.jsdelivr.net/npm/tinymce-i18n@23.10.9/langs6/ga.min.js')->loadedOnRequest(),
            Js::make('tinymce-lang-gl', 'https://cdn.jsdelivr.net/npm/tinymce-i18n@23.10.9/langs6/gl.min.js')->loadedOnRequest(),
            Js::make('tinymce-lang-he_IL', 'https://cdn.jsdelivr.net/npm/tinymce-i18n@23.10.9/langs6/he_IL.min.js')->loadedOnRequest(),
            Js::make('tinymce-lang-hr', 'https://cdn.jsdelivr.net/npm/tinymce-i18n@23.10.9/langs6/hr.min.js')->loadedOnRequest(),
            Js::make('tinymce-lang-hu_HU', 'https://cdn.jsdelivr.net/npm/tinymce-i18n@23.10.9/langs6/hu_HU.min.js')->loadedOnRequest(),
            Js::make('tinymce-lang-hy', 'https://cdn.jsdelivr.net/npm/tinymce-i18n@23.10.9/langs6/hy.min.js')->loadedOnRequest(),
            Js::make('tinymce-lang-id', 'https://cdn.jsdelivr.net/npm/tinymce-i18n@23.10.9/langs6/id.min.js')->loadedOnRequest(),
            Js::make('tinymce-lang-is_IS', 'https://cdn.jsdelivr.net/npm/tinymce-i18n@23.10.9/langs6/is_IS.min.js')->loadedOnRequest(),
            Js::make('tinymce-lang-it', 'https://cdn.jsdelivr.net/npm/tinymce-i18n@23.10.9/langs6/it.min.js')->loadedOnRequest(),
            Js::make('tinymce-lang-ja', 'https://cdn.jsdelivr.net/npm/tinymce-i18n@23.10.9/langs6/ja.min.js')->loadedOnRequest(),
            Js::make('tinymce-lang-kab', 'https://cdn.jsdelivr.net/npm/tinymce-i18n@23.10.9/langs6/kab.min.js')->loadedOnRequest(),
            Js::make('tinymce-lang-kk', 'https://cdn.jsdelivr.net/npm/tinymce-i18n@23.10.9/langs6/kk.min.js')->loadedOnRequest(),
            Js::make('tinymce-lang-ko_KR', 'https://cdn.jsdelivr.net/npm/tinymce-i18n@23.10.9/langs6/ko_KR.min.js')->loadedOnRequest(),
            Js::make('tinymce-lang-ku', 'https://cdn.jsdelivr.net/npm/tinymce-i18n@23.10.9/langs6/ku.min.js')->loadedOnRequest(),
            Js::make('tinymce-lang-lt', 'https://cdn.jsdelivr.net/npm/tinymce-i18n@23.10.9/langs6/lt.min.js')->loadedOnRequest(),
            Js::make('tinymce-lang-lv', 'https://cdn.jsdelivr.net/npm/tinymce-i18n@23.10.9/langs6/lv.min.js')->loadedOnRequest(),
            Js::make('tinymce-lang-nb_NO', 'https://cdn.jsdelivr.net/npm/tinymce-i18n@23.10.9/langs6/nb_NO.min.js')->loadedOnRequest(),
            Js::make('tinymce-lang-nl', 'https://cdn.jsdelivr.net/npm/tinymce-i18n@23.10.9/langs6/nl.min.js')->loadedOnRequest(),
            Js::make('tinymce-lang-nl_BE', 'https://cdn.jsdelivr.net/npm/tinymce-i18n@23.10.9/langs6/nl_BE.min.js')->loadedOnRequest(),
            Js::make('tinymce-lang-oc', 'https://cdn.jsdelivr.net/npm/tinymce-i18n@23.10.9/langs6/oc.min.js')->loadedOnRequest(),
            Js::make('tinymce-lang-pl', 'https://cdn.jsdelivr.net/npm/tinymce-i18n@23.10.9/langs6/pl.min.js')->loadedOnRequest(),
            Js::make('tinymce-lang-pt_BR', 'https://cdn.jsdelivr.net/npm/tinymce-i18n@23.10.9/langs6/pt_BR.min.js')->loadedOnRequest(),
            Js::make('tinymce-lang-ro', 'https://cdn.jsdelivr.net/npm/tinymce-i18n@23.10.9/langs6/ro.min.js')->loadedOnRequest(),
            Js::make('tinymce-lang-ru', 'https://cdn.jsdelivr.net/npm/tinymce-i18n@23.10.9/langs6/ru.min.js')->loadedOnRequest(),
            Js::make('tinymce-lang-sk', 'https://cdn.jsdelivr.net/npm/tinymce-i18n@23.10.9/langs6/sk.min.js')->loadedOnRequest(),
            Js::make('tinymce-lang-sl_SI', 'https://cdn.jsdelivr.net/npm/tinymce-i18n@23.10.9/langs6/sl_SI.min.js')->loadedOnRequest(),
            Js::make('tinymce-lang-sq', 'https://cdn.jsdelivr.net/npm/tinymce-i18n@23.10.9/langs6/sq.min.js')->loadedOnRequest(),
            Js::make('tinymce-lang-sr', 'https://cdn.jsdelivr.net/npm/tinymce-i18n@23.10.9/langs6/sr.min.js')->loadedOnRequest(),
            Js::make('tinymce-lang-sv_SE', 'https://cdn.jsdelivr.net/npm/tinymce-i18n@23.10.9/langs6/sv_SE.min.js')->loadedOnRequest(),
            Js::make('tinymce-lang-ta', 'https://cdn.jsdelivr.net/npm/tinymce-i18n@23.10.9/langs6/ta.min.js')->loadedOnRequest(),
            Js::make('tinymce-lang-tg', 'https://cdn.jsdelivr.net/npm/tinymce-i18n@23.10.9/langs6/tg.min.js')->loadedOnRequest(),
            Js::make('tinymce-lang-th_TH', 'https://cdn.jsdelivr.net/npm/tinymce-i18n@23.10.9/langs6/th_TH.min.js')->loadedOnRequest(),
            Js::make('tinymce-lang-tr', 'https://cdn.jsdelivr.net/npm/tinymce-i18n@23.10.9/langs6/tr.min.js')->loadedOnRequest(),
            Js::make('tinymce-lang-ug', 'https://cdn.jsdelivr.net/npm/tinymce-i18n@23.10.9/langs6/ug.min.js')->loadedOnRequest(),
            Js::make('tinymce-lang-uk', 'https://cdn.jsdelivr.net/npm/tinymce-i18n@23.10.9/langs6/uk.min.js')->loadedOnRequest(),
            Js::make('tinymce-lang-vi', 'https://cdn.jsdelivr.net/npm/tinymce-i18n@23.10.9/langs6/vi.min.js')->loadedOnRequest(),
		], package: 'amidesfahani/filament-tinyeditor');
	}
}