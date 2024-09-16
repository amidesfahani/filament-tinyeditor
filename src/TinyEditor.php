<?php

namespace AmidEsfahani\FilamentTinyEditor;

use Filament\Forms\Components\Concerns;
use Filament\Forms\Components\Concerns\HasExtraInputAttributes;
use Filament\Forms\Components\Contracts;
use Filament\Forms\Components\Field;
use Filament\Support\Concerns\HasExtraAlpineAttributes;

class TinyEditor extends Field implements Contracts\CanBeLengthConstrained, Contracts\HasFileAttachments
{
    use Concerns\CanBeLengthConstrained;
    use Concerns\HasFileAttachments;
    use Concerns\HasPlaceholder;
    use HasExtraAlpineAttributes;
    use HasExtraInputAttributes;

    protected string $view = 'filament-tinyeditor::tiny-editor';
    protected string $profile = 'default';
    protected bool $isSimple = false;
    protected string $direction;
    protected int $width = 0;
    protected int $height = 0;
    protected int $maxHeight = 0;
    protected int $minHeight = 500;
    protected int $previewMaxHeight = 0;
    protected int $previewMinHeight = 0;
    protected int $tinyMaxWidth = 0;
    protected int $minWidth = 500;
    protected int $previewMaxWidth = 0;
    protected int $previewMinWidth = 0;
    protected string $toolbar;
    protected bool $toolbarSticky = true;
    protected int $toolbarStickyOffset = 64;
    protected string $toolbarMode = 'floating';
    protected string $toolbarLocation = 'auto';
    protected bool $inlineOption = false;
    protected bool $toolbarPersist = false;
    protected bool $showMenuBar = false;
    protected array $externalPlugins;
    protected array|\Closure  $customConfigs = [];
    protected bool $relativeUrls = false;
    protected bool $removeScriptHost = true;
    protected bool $convertUrls = true;
    protected string|bool $darkMode;
    protected string $skinsUI;
    protected string $skinsContent;
    protected string|\Closure $language;
    protected string|array|bool|\Closure $imageList = false;
    protected string|array|bool $imageClassList = false;
    protected string|bool|\Closure $imagesUploadUrl = false;
    protected bool $imageAdvtab = false;
    protected bool $imageDescription = true;
    protected bool|string $resize = false;

    protected string $tiny;
    protected string $languageVersion;
    protected string $languagePackage;

    protected function setUp(): void
    {
        parent::setUp();

        $this->tiny = Tiny::version();
        $this->languageVersion = Tiny::languageVersion();
        $this->languagePackage = Tiny::languagePackage();

        $this->language = app()->getLocale();
        $this->direction = config('filament-tinyeditor.direction', 'ltr');
        $this->darkMode = config('filament-tinyeditor.darkMode', 'auto');
        $this->skinsUI = config('filament-tinyeditor.skins.ui', 'oxide');
        $this->skinsContent = config('filament-tinyeditor.skins.content', 'default');
    }

    public function getToolbar(): string
    {
        $toolbar = 'undo redo removeformat | styles | bold italic | rtl ltr | alignjustify alignright aligncenter alignleft | numlist bullist outdent indent accordion | forecolor backcolor | blockquote table toc hr | image link anchor media codesample emoticons | visualblocks print preview wordcount fullscreen help';
        if ($this->isSimple()) {
            $toolbar = 'removeformat | bold italic | rtl ltr | link emoticons';
        }

        if (config('filament-tinyeditor.profiles.' . $this->profile . '.toolbar')) {
            $toolbar = config('filament-tinyeditor.profiles.' . $this->profile . '.toolbar');
        }

        return $toolbar;
    }

    public function setCustomConfigs(array|\Closure $configs): static
    {
        $this->customConfigs = $configs;

        return $this;
    }

    public function getCustomConfigs(): string
    {
        $defaultConfigs = config("filament-tinyeditor.profiles.{$this->profile}.custom_configs", []);
        $customConfigs = $this->evaluate($this->customConfigs) ?? [];

        $mergedConfigs = array_replace_recursive($customConfigs, $defaultConfigs);

        if (empty($mergedConfigs)) {
            return '{}';
        }

        return str_replace('"', "'", json_encode($mergedConfigs, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
    }

    public function getPlugins(): string
    {
        $plugins = 'accordion autoresize codesample directionality advlist autolink link image lists charmap preview anchor pagebreak searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media table emoticons help';

        if ($this->isSimple()) {
            $plugins = 'autoresize directionality emoticons link wordcount';
        }

        if (config('filament-tinyeditor.profiles.' . $this->profile . '.plugins')) {
            $plugins = config('filament-tinyeditor.profiles.' . $this->profile . '.plugins');
        }

        return $plugins;
    }

    public function isSimple(): bool
    {
        return (bool) $this->evaluate($this->isSimple);
    }

    public function getFileAttachmentsDirectory(): ?string
    {
        return filled($directory = $this->evaluate($this->fileAttachmentsDirectory)) ? $directory : config('filament-tinyeditor.profiles.' . $this->profile . '.upload_directory');
    }

    public function language(string | \Closure $language): static
    {
        $this->language = $language;

        return $this;
    }

    public function getInterfaceLanguage(): string
    {
        return match ($this->evaluate($this->language)) {
            'ar' => 'ar',
            'az' => 'az',
            'bg' => 'bg_BG',
            'bn' => 'bn_BD',
            'ca' => 'ca',
            'cs' => 'cs',
            'cy' => 'cy',
            'da' => 'da',
            'de' => 'de',
            'dv' => 'dv',
            'el' => 'el',
            'eo' => 'eo',
            'es' => 'es',
            'et' => 'et',
            'eu' => 'eu',
            'fa' => 'fa',
            'fi' => 'fi',
            'fr' => 'fr_FR',
            'ga' => 'ga',
            'gl' => 'gl',
            'he' => 'he_IL',
            'hr' => 'hr',
            'hu' => 'hu_HU',
            'hy' => 'hy',
            'id' => 'id',
            'is' => 'is_IS',
            'it' => 'it',
            'ja' => 'ja',
            'kab' => 'kab',
            'kk' => 'kk',
            'ko' => 'ko_KR',
            'ku' => 'ku',
            'lt' => 'lt',
            'lv' => 'lv',
            'nb' => 'nb_NO',
            'nl' => 'nl',
            'oc' => 'oc',
            'pl' => 'pl',
            'pt_PT' => 'pt_PT',
            'pt_BR' => 'pt_BR',
            'ro' => 'ro',
            'ru' => 'ru',
            'sk' => 'sk',
            'sl' => 'sl',
            'sq' => 'sq',
            'sr' => 'sr',
            'sv' => 'sv_SE',
            'ta' => 'ta',
            'tg' => 'tg',
            'th' => 'th_TH',
            'tr' => 'tr',
            'ug' => 'ug',
            'uk' => 'uk',
            'vi' => 'vi',
            'zh' => 'zh-Hans',
            'zh-CN' => 'zh-Hans',
            'zh-TW' => 'zh-Hant',
            'zh-HK' => 'zh_HK',
            'zh-MO' => 'zh_MO',
            'zh-SG' => 'zh_SG',
            default => 'en',
        };
    }

    public function getLanguageId(): string
    {
        return match ($this->getInterfaceLanguage()) {
            'ar' => 'tinymce-lang-ar',
            'az' => 'tinymce-lang-az',
            'bg' => 'tinymce-lang-bg_BG',
            'bn' => 'tinymce-lang-bn_BD',
            'ca' => 'tinymce-lang-ca',
            'cs' => 'tinymce-lang-cs',
            'cy' => 'tinymce-lang-cy',
            'da' => 'tinymce-lang-da',
            'de' => 'tinymce-lang-de',
            'dv' => 'tinymce-lang-dv',
            'el' => 'tinymce-lang-el',
            'eo' => 'tinymce-lang-eo',
            'es' => 'tinymce-lang-es',
            'et' => 'tinymce-lang-et',
            'eu' => 'tinymce-lang-eu',
            'fa' => 'tinymce-lang-fa',
            'fi' => 'tinymce-lang-fi',
            'fr' => 'tinymce-lang-fr_FR',
            'ga' => 'tinymce-lang-ga',
            'gl' => 'tinymce-lang-gl',
            'he' => 'tinymce-lang-he_IL',
            'hr' => 'tinymce-lang-hr',
            'hu' => 'tinymce-lang-hu_HU',
            'hy' => 'tinymce-lang-hy',
            'id' => 'tinymce-lang-id',
            'is' => 'tinymce-lang-is_IS',
            'it' => 'tinymce-lang-it',
            'ja' => 'tinymce-lang-ja',
            'kab' => 'tinymce-lang-kab',
            'kk' => 'tinymce-lang-kk',
            'ko' => 'tinymce-lang-ko_KR',
            'ku' => 'tinymce-lang-ku',
            'lt' => 'tinymce-lang-lt',
            'lv' => 'tinymce-lang-lv',
            'nb' => 'tinymce-lang-nb_NO',
            'nl' => 'tinymce-lang-nl',
            'oc' => 'tinymce-lang-oc',
            'pl' => 'tinymce-lang-pl',
            'pt_PT' => 'tinymce-lang-pt_PT',
            'pt_BR' => 'tinymce-lang-pt_BR',
            'ro' => 'tinymce-lang-ro',
            'ru' => 'tinymce-lang-ru',
            'sk' => 'tinymce-lang-sk',
            'sl' => 'tinymce-lang-sl',
            'sq' => 'tinymce-lang-sq',
            'sr' => 'tinymce-lang-sr',
            'sv' => 'tinymce-lang-sv_SE',
            'ta' => 'tinymce-lang-ta',
            'tg' => 'tinymce-lang-tg',
            'th' => 'tinymce-lang-th_TH',
            'tr' => 'tinymce-lang-tr',
            'ug' => 'tinymce-lang-ug',
            'uk' => 'tinymce-lang-uk',
            'vi' => 'tinymce-lang-vi',
            'zh' => 'tinymce-lang-zh_CN',
            'zh-cn' => 'tinymce-lang-zh_CN',
            'zh-tw' => 'tinymce-lang-zh_TW',
            'zh-hk' => 'tinymce-lang-zh_HK',
            'zh-mo' => 'tinymce-lang-zh_MO',
            'zh-sg' => 'tinymce-lang-zh_SG',
            default => 'tinymce',
        };
    }

    public function getDirection()
    {
        if (!$this->direction || $this->direction == 'auto') {
            return match ($this->getInterfaceLanguage()) {
                'ar' => 'rtl',
                'fa' => 'rtl',
                default => 'ltr',
            };
        }

        return $this->direction;
    }

    public function direction(string $direction)
    {
        $this->direction = $direction;

        return $this;
    }

    public function rtl()
    {
        $this->direction = 'rtl';

        return $this;
    }

    public function ltr()
    {
        $this->direction = 'ltr';

        return $this;
    }

    public function profile(string $profile): static
    {
        $this->profile = $profile;

        return $this;
    }

    public function darkMode(): string | bool
    {
        return $this->darkMode;
    }

    public function skinsUI(): string
    {
        return $this->skinsUI;
    }

    public function skinsContent(): string
    {
        return $this->skinsContent;
    }

    /** */
    public function getWidth(): int
    {
        return $this->width;
    }

    public function width(int $width): static
    {
        $this->width = $width;

        return $this;
    }

    public function getTinyMaxWidth(): int
    {
        return $this->tinyMaxWidth;
    }

    public function maxTinyWidth(int $maxWidth): static
    {
        $this->tinyMaxWidth = $maxWidth;

        return $this;
    }

    public function getMinWidth(): int
    {
        return $this->minWidth;
    }

    public function minWidth(int $minWidth): static
    {
        $this->minWidth = $minWidth;

        return $this;
    }

    public function getPreviewMaxWidth(): int
    {
        return $this->previewMaxWidth;
    }

    public function previewMaxWidth(int $previewMaxWidth): static
    {
        $this->previewMaxWidth = $previewMaxWidth;

        return $this;
    }

    public function getPreviewMinWidth(): int
    {
        return $this->previewMinWidth;
    }

    public function previewMinWidth(int $previewMinWidth): static
    {
        $this->previewMinWidth = $previewMinWidth;

        return $this;
    }

    public function getHeight(): int
    {
        return $this->height;
    }

    public function height(int $height): static
    {
        $this->height = $height;

        return $this;
    }

    public function getMaxHeight(): int
    {
        return $this->maxHeight;
    }

    public function maxHeight(int $maxHeight): static
    {
        $this->maxHeight = $maxHeight;

        return $this;
    }

    public function getMinHeight(): int
    {
        return $this->minHeight;
    }

    public function minHeight(int $minHeight): static
    {
        $this->minHeight = $minHeight;

        return $this;
    }

    public function getPreviewMaxHeight(): int
    {
        return $this->previewMaxHeight;
    }

    public function previewMaxHeight(int $previewMaxHeight): static
    {
        $this->previewMaxHeight = $previewMaxHeight;

        return $this;
    }

    public function getPreviewMinHeight(): int
    {
        return $this->previewMinHeight;
    }

    public function previewMinHeight(int $previewMinHeight): static
    {
        $this->previewMinHeight = $previewMinHeight;

        return $this;
    }

    public function getResize(): bool|string
    {
        return is_bool($this->resize) ? $this->resize : "'$this->resize'";
    }

    public function resize(bool|string $resize): static
    {
        $this->resize = $resize;

        return $this;
    }

    public function getToolbarSticky(): bool
    {
        return $this->toolbarSticky;
    }

    public function toolbarSticky(bool $toolbarSticky): static
    {
        $this->toolbarSticky = $toolbarSticky;

        return $this;
    }

    public function getToolbarStickyOffset(): int
    {
        return $this->toolbarStickyOffset;
    }

    public function toolbarStickyOffset(int $toolbarStickyOffset): static
    {
        $this->toolbarStickyOffset = $toolbarStickyOffset;

        return $this;
    }

    public function getToolbarMode(): string
    {
        return $this->toolbarMode;
    }

    public function toolbarMode(string $toolbarMode): static
    {
        $this->toolbarMode = $toolbarMode;

        return $this;
    }

    public function getToolbarLocation(): string
    {
        return $this->toolbarLocation;
    }

    public function toolbarLocation(string $toolbarLocation): static
    {
        $this->toolbarLocation = $toolbarLocation;

        return $this;
    }

    public function getInlineOption(): bool
    {
        return $this->inlineOption;
    }

    public function inlineTiny(bool $inlineOption): static
    {
        $this->inlineOption = $inlineOption;

        return $this;
    }

    public function getToolbarPersist(): bool
    {
        return $this->toolbarPersist;
    }

    public function toolbarPersist(bool $toolbarPersist): static
    {
        $this->toolbarPersist = $toolbarPersist;

        return $this;
    }

    public function getShowMenuBar(): bool
    {
        return $this->showMenuBar;
    }

    public function showMenuBar(): static
    {
        $this->showMenuBar = true;

        return $this;
    }

    public function getRelativeUrls(): bool
    {
        return $this->relativeUrls;
    }

    public function setRelativeUrls(bool $relativeUrls): static
    {
        $this->relativeUrls = $relativeUrls;

        return $this;
    }

    public function getRemoveScriptHost(): bool
    {
        return $this->removeScriptHost;
    }

    public function setRemoveScriptHost(bool $removeScriptHost): static
    {
        $this->removeScriptHost = $removeScriptHost;

        return $this;
    }

    public function getConvertUrls(): bool
    {
        return $this->convertUrls;
    }

    public function setConvertUrls(bool $convertUrls): static
    {
        $this->convertUrls = $convertUrls;

        return $this;
    }

    public function getExternalPlugins(): string
    {
        if (config('filament-tinyeditor.profiles.' . $this->profile . '.external_plugins')) {
            return str_replace('"', "'", json_encode(config('filament-tinyeditor.profiles.' . $this->profile . '.external_plugins')));
        }

        return '{}';
    }

    public function setExternalPlugins(array $plugins): static
    {
        $this->externalPlugins = $plugins;

        return $this;
    }

    public function imageList(string|array|\Closure $list): static
    {
        if (is_array($list)) {
            $list = str_replace('"', "'", json_encode($list));
        }

        $this->imageList = $list;

        return $this;
    }

    public function getImageList(): string | bool
    {
        if (! $this->imageList) {
            return config('filament-tinyeditor.profiles.' . $this->profile . '.image_list') ?? 'false';
        }

        if (is_string($this->imageList)) {
            return $this->imageList;
        }

        $imageList = $this->evaluate($this->imageList);

        return str_replace('"', "'", json_encode($imageList));
    }

    public function imageClassList(string|array $list): static
    {
        if (is_array($list)) {
            $list = str_replace('"', "'", json_encode($list));
        }

        $this->imageClassList = $list;

        return $this;
    }

    public function getImageClassList(): ?string
    {
        if (!$this->imageClassList) {
            return null;
        }
        return $this->imageClassList;
    }

    public function imageAdvtab(): bool
    {
        return $this->imageAdvtab ?? false;
    }

    public function imageDescription(bool $imageDescription): static
    {
        $this->imageDescription = $imageDescription;

        return $this;
    }

    public function getImageDescription(): bool
    {
        return config('filament-tinyeditor.profiles.' . $this->profile . '.image_description') ?? $this->imageDescription;
    }

    public function imagesUploadUrl(string | \Closure $url): static
    {
        $this->imagesUploadUrl = $url;

        return $this;
    }

    public function getImagesUploadUrl(): string | bool
    {
        if (! $this->imagesUploadUrl) {
            return config('filament-tinyeditor.profiles.' . $this->profile . '.images_upload_url') ?? '';
        }

        return $this->evaluate($this->imagesUploadUrl);
    }

    public function options(array $options): static
    {
        foreach ($options as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }

        return $this;
    }

    public function getLanguageURL($lang): string
    {
        return Tiny::getLanguageURL($lang);
    }

    public function getFontSizes(): string
    {
        return config('filament-tinyeditor.extra.toolbar.fontsize', '');
    }

    public function getFontFamilies(): string
    {
        return config('filament-tinyeditor.extra.toolbar.fontfamily', '');
    }

    public function getLicenseKey(): string
    {
        return config('filament-tinyeditor.license_key', 'gpl');
    }
}
