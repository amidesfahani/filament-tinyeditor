<?php

namespace AmidEsfahani\FilamentTinyEditor;

use Filament\Forms\Components\Concerns;
use Filament\Forms\Components\Concerns\HasExtraInputAttributes;
use Filament\Forms\Components\Contracts;
use Filament\Forms\Components\Field;
use Filament\Support\Concerns\HasExtraAlpineAttributes;
use Illuminate\Support\Str;

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
    protected int $maxHeight = 0;
    protected int $minHeight = 500;
    protected int $previewMaxHeight = 0;
    protected int $previewMinHeight = 0;
    protected string $toolbar;
    protected bool $toolbarSticky = false;
    protected bool $showMenuBar = false;
    protected array $externalPlugins;
    protected bool $relativeUrls = false;
    protected bool $removeScriptHost = true;
    protected bool $convertUrls = true;
    protected string $templates = '';
    protected string|bool $darkMode;
    protected string $skinsUI;
    protected string $skinsContent;
    protected string|\Closure $language;
    protected string|array|bool $imageList = false;
    protected string|array|bool $imageClassList = false;
    protected bool $imageAdvtab = false;
    protected bool $imageDescription = true;

    protected function setUp(): void
    {
        parent::setUp();

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

        if (! Str::contains($this->templates, 'template')) {
            $toolbar .= ' template';
        }

        return $toolbar;
    }

    public function getPlugins(): string
    {
        $plugins = 'accordion autoresize codesample directionality advlist autolink link image lists charmap preview anchor pagebreak searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media table emoticons template help';

        if ($this->isSimple()) {
            $plugins = 'autoresize directionality emoticons link wordcount';
        }

        if (config('filament-tinyeditor.profiles.' . $this->profile . '.plugins')) {
            $plugins = config('filament-tinyeditor.profiles.' . $this->profile . '.plugins');
        }

        if (! Str::contains($this->templates, 'template')) {
            $plugins .= ' template';
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

    public function templates(string $templates): static
    {
        $this->templates = addslashes($templates);

        return $this;
    }

    public function getTemplates(): string
    {
        return $this->templates;
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
            'pt' => 'pt_BR',
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
            'zh' => 'zh_CN',
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
            'pt' => 'tinymce-lang-pt_BR',
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
            default => 'tinymce',
        };
    }

    public function getDirection()
    {
        if (! $this->direction || $this->direction == 'auto') {
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

    public function getCustomConfigs(): string
    {
        if (config('filament-tinyeditor.profiles.' . $this->profile . '.custom_configs')) {
            return str_replace('"', "'", json_encode(config('filament-tinyeditor.profiles.' . $this->profile . '.custom_configs')));
        }

        return '';
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

    public function getToolbarSticky(): bool
    {
        return $this->toolbarSticky;
    }

    public function toolbarSticky(bool $toolbarSticky): static
    {
        $this->toolbarSticky = $toolbarSticky;

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

    public function getExternalPlugins(): array
    {
        return $this->externalPlugins ?? [];
    }

    public function setExternalPlugins(array $plugins): static
    {
        $this->externalPlugins = $plugins;

        return $this;
    }

    public function imageList(string|array $list): static
    {
        if (is_array($list)) {
            $list = str_replace('"', "'", json_encode($list));
        }

        $this->imageList = $list;

        return $this;
    }

    public function getImageList(): string|bool
    {
        if (!$this->imageList) {
            return 'false';
        }
        return $this->imageList;
    }

    public function imageClassList(string|array $list): static
    {
        if (is_array($list)) {
            $list = str_replace('"', "'", json_encode($list));
        }

        $this->imageClassList = $list;

        return $this;
    }

    public function getImageClassList(): string|bool
    {
        if (!$this->imageClassList) {
            return 'false';
        }
        return $this->imageClassList;
    }

    public function imageAdvtab(): bool
    {
        return $this->imageAdvtab ?? false;
    }

    public function imageDescription(): bool
    {
        return $this->imageDescription ?? true;
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
}
