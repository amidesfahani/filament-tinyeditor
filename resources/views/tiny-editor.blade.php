@php
    $extraAttributeBag = $getExtraAttributeBag();
    $fieldWrapperView = $getFieldWrapperView();
    $id = $getId();
    $isDisabled = $isDisabled();
    $livewireKey = $getLivewireKey();
    $key = $getKey();
    $statePath = $getStatePath();
@endphp

<x-dynamic-component :component="$fieldWrapperView" :field="$field" class="relative z-0">
    @php
        $textareaID = 'tiny-editor-' . str_replace(['.', '#', '$'], '-', $getId()) . '-' . rand();
    @endphp

    <div x-data="{ isModalOpen: false }" x-init="$el.closest('.fi-modal')?.addEventListener('open-tinyeditor-modal', () => { isModalOpen = true; });
    $el.closest('.fi-modal')?.addEventListener('close-tinyeditor-modal', () => { isModalOpen = false; });
    $watch('isModalOpen', value => { $dispatch('modal-visibility-changed', { isOpen: value }); });">
        <x-filament::input.wrapper :valid="!$errors->has($statePath)" x-cloak :attributes="\Filament\Support\prepare_inherited_attributes($extraAttributeBag)">
            <div x-load
                x-load-src="{{ \Filament\Support\Facades\FilamentAsset::getAlpineComponentSrc('tinyeditor', 'amidesfahani/filament-tinyeditor') }}"
                x-load-css="[@js(\Filament\Support\Facades\FilamentAsset::getStyleHref('tiny-css', package: 'amidesfahani/filament-tinyeditor'))]" x-load-js="[@js(\Filament\Support\Facades\FilamentAsset::getScriptSrc($getLanguageId(), package: 'amidesfahani/filament-tinyeditor'))]"
                x-data="tinyeditor({
                    state: $wire.{{ $applyStateBindingModifiers("\$entangle('{$statePath}')", isOptimisticallyLive: false) }},
                    statePath: @js($statePath),
                
                    selector: '#{{ $textareaID }}',
                    plugins: '{{ $getPlugins() }}',
                    external_plugins: {{ $getExternalPlugins() }},
                    toolbar: '{{ $getToolbar() }}',
                    @if (!$getTextPattern()) text_patterns: @js($getTextPattern()), @endif
                    language: '{{ $getInterfaceLanguage() }}',
                    language_url: '{{ $getLanguageURL($getInterfaceLanguage()) }}',
                    directionality: '{{ $getDirection() }}',
                    @if ($getHeight()) height: @js($getHeight()), @endif
                    @if ($getMaxHeight()) max_height: @js($getMaxHeight()), @endif
                    @if ($getMinHeight()) min_height: @js($getMinHeight()), @endif
                    @if ($getWidth()) width: @js($getWidth()), @endif
                    @if ($getTinyMaxWidth()) max_width: @js($getTinyMaxWidth()), @endif
                    @if ($getMinWidth()) min_width: @js($getMinWidth()), @endif
                    resize: @js($getResize()),
                    @if (!filament()->hasDarkModeForced() && $darkMode() == 'media') skin: (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'oxide-dark' : 'oxide'),
                    content_css: (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'default'),
                    @elseif(!filament()->hasDarkModeForced() && $darkMode() == 'class')
                    skin: (document.querySelector('html').getAttribute('class').includes('dark') ? 'oxide-dark' : 'oxide'),
                    content_css: (document.querySelector('html').getAttribute('class').includes('dark') ? 'dark' : 'default'),
                    @elseif(filament()->hasDarkModeForced() || $darkMode() == 'force')
                    skin: 'oxide-dark',
                    content_css: 'dark',
                    @elseif(!filament()->hasDarkModeForced() && $darkMode() == false)
                    skin: 'oxide',
                    content_css: 'default',
                    @elseif(!filament()->hasDarkModeForced() && $darkMode() == 'custom')
                    skin: '{{ $skinsUI() }}',
                    content_css: '{{ $skinsContent() }}',
                    @else
                    skin: ((localStorage.getItem('theme') ?? 'system') == 'dark' || (localStorage.getItem('theme') === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches)) ? 'oxide-dark' : 'oxide',
                    content_css: ((localStorage.getItem('theme') ?? 'system') == 'dark' || (localStorage.getItem('theme') === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches)) ? 'dark' : 'default', @endif
                    toolbar_sticky: {{ $getToolbarSticky() ? 'true' : 'false' }},
                    toolbar_sticky_offset: {{ $getToolbarStickyOffset() }},
                    toolbar_mode: '{{ $getToolbarMode() }}',
                    toolbar_location: '{{ $getToolbarLocation() }}',
                    inline: {{ $getInlineOption() ? 'true' : 'false' }},
                    toolbar_persist: {{ $getToolbarPersist() ? 'true' : 'false' }},
                    menubar: {{ $getShowMenuBar() ? 'true' : 'false' }},
                    relative_urls: {{ $getRelativeUrls() ? 'true' : 'false' }},
                    remove_script_host: {{ $getRemoveScriptHost() ? 'true' : 'false' }},
                    convert_urls: {{ $getConvertUrls() ? 'true' : 'false' }},
                    font_size_formats: '{{ $getFontSizes() }}',
                    fontfamily: '{{ $getFontFamilies() }}',
                    setup: null,
                    disabled: @js($isDisabled),
                    locale: '{{ app()->getLocale() }}',
                    placeholder: @js($getPlaceholder()),
                    image_list: {!! $getImageList() !!},
                    @if ($getImagesUploadUrl !== false) images_upload_url: @js($getImagesUploadUrl()), @endif
                    image_advtab: @js($imageAdvtab()),
                    image_description: @js($getImageDescription()),
                    image_class_list: @js($getImageClassList()),
                    license_key: '{{ $getLicenseKey() }}',
                    custom_configs: {{ $getCustomConfigs() }},
                    uploadingMessage: '{{ $getUploadingMessage() ?: 'Uploading image...' }}',
                    key: '{{ $getKey() }}',
                })" wire:ignore
                wire:key="{{ $livewireKey }}.{{ substr(md5(serialize([$isDisabled])), 0, 64) }}">
                @if ($isDisabled)
                    <div x-html="state" @style(['max-height: ' . $getPreviewMaxHeight() . 'px' => $getPreviewMaxHeight() > 0, 'min-height: ' . $getPreviewMinHeight() . 'px' => $getPreviewMinHeight() > 0])
                        class="prose dark:prose-invert block w-full max-w-none overflow-y-auto rounded-lg border border-gray-300 bg-white p-3 opacity-70 shadow-sm transition duration-75 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                    </div>
                @else
                    <input id="{{ $textareaID }}" type="hidden" x-ref="tinymce"
                        placeholder="{{ $getPlaceholder() }}">
                @endif
            </div>
        </x-filament::input.wrapper>
    </div>

</x-dynamic-component>
