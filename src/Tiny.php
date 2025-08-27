<?php

namespace AmidEsfahani\FilamentTinyEditor;

class Tiny
{
	public static function version()
	{
		return config('filament-tinyeditor.version.tiny', '8.0.2');
	}

	public static function languageVersion()
	{
		return config('filament-tinyeditor.version.language.version', '25.8.4');
	}

	public static function languagePackage()
	{
		return config('filament-tinyeditor.version.language.package', 'langs8');
	}

	public static array $languages = [
		'ar',
		'az',
		'bg_BG',
		'bn_BD',
		'ca',
		'cs',
		'cy',
		'da',
		'de',
		'dv',
		'el',
		'eo',
		'es',
		'et',
		'es_MX',
		'eu',
		'fa',
		'fi',
		'fr_FR',
		'ga',
		'gl',
		'he_IL',
		'hr',
		'hu_HU',
		'hy',
		'id',
		'is_IS',
		'it',
		'ja',
		'kab',
		'kk',
		'ko_KR',
		'ku',
		'lt',
		'lv',
		'nb_NO',
		'nl',
		'nl_BE',
		'oc',
		'pl',
		'pt_BR',
		'ro',
		'ru',
		'sk',
		'sl_SI',
		'sq',
		'sr',
		'sv_SE',
		'ta',
		'tg',
		'th_TH',
		'tr',
		'ug',
		'uk',
		'vi',
	];

	public static function getLanguageURL($lang): string
	{
		return 'https://cdn.jsdelivr.net/npm/tinymce-i18n@' . static::languageVersion() . '/' . static::languagePackage() . '/' . $lang . '.min.js';
	}

	public static function getLanguages(): array
	{
		$languages = [];
		foreach (static::$languages as $lang) {
			$languages['tinymce-lang-' . $lang] = static::getLanguageURL($lang);
		}

		return $languages;
	}
}
