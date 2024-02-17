@php
    $statePath = $getStatePath();
@endphp

<x-dynamic-component
    :component="$getFieldWrapperView()"
    :field="$field"
    class="relative z-0"
>
	@php
		$textareaID = 'tiny-editor-' . str_replace(['.', '#', '$'], '-', $getId()) . '-' . rand();
	@endphp

    <div
		wire:ignore
		x-ignore
		ax-load
		ax-load-src="{{ \Filament\Support\Facades\FilamentAsset::getAlpineComponentSrc('tinyeditor', 'amidesfahani/filament-tinyeditor') }}"
		x-load-css="[@js(\Filament\Support\Facades\FilamentAsset::getStyleHref('tiny-css', package: 'amidesfahani/filament-tinyeditor'))]"
        x-load-js="[@js(\Filament\Support\Facades\FilamentAsset::getScriptSrc($getLanguageId(), package: 'amidesfahani/filament-tinyeditor'))]"
		x-data="tinyeditor({
			state: $wire.{{ $applyStateBindingModifiers("entangle('{$statePath}')", isOptimisticallyLive: false) }},
			statePath: '{{ $statePath }}',
			selector: '#{{ $textareaID }}',
			plugins: '{{ $getPlugins() }}',
			toolbar: '{{ $getToolbar() }}',
			language: '{{ $getInterfaceLanguage() }}',
			language_url: 'https://cdn.jsdelivr.net/npm/tinymce-i18n@23.10.9/langs6/{{ $getInterfaceLanguage() }}.min.js',
			directionality: '{{ $getDirection() }}',
			max_height: {{ $getMaxHeight() }},
			min_height: {{ $getMinHeight() }},
			@if($darkMode() == 'media')
			skin: (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'oxide-dark' : 'oxide'),
			content_css: (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'default'),
			@elseif($darkMode() == 'class')
			skin: (document.querySelector('html').getAttribute('class').includes('dark') ? 'oxide-dark' : 'oxide'),
			content_css: (document.querySelector('html').getAttribute('class').includes('dark') ? 'dark' : 'default'),
			@elseif($darkMode() == 'force')
			skin: 'oxide-dark',
			content_css: 'dark',
			@elseif($darkMode() == false)
			skin: 'oxide',
			content_css: 'default',
			@elseif($darkMode() == 'custom')
			skin: '{{ $skinsUI() }}',
			content_css: '{{ $skinsContent() }}',
			@else
			skin: (window.matchMedia('(prefers-color-scheme: dark)').matches || document.querySelector('html').getAttribute('class').includes('dark') ? 'oxide-dark' : 'oxide'),
			content_css: (window.matchMedia('(prefers-color-scheme: dark)').matches || document.querySelector('html').getAttribute('class').includes('dark') ? 'dark' : 'default'),
			@endif
			toolbar_sticky: {{ $getToolbarSticky() ? 'true' : 'false' }},
			templates: '{{ $getTemplates() }}',
			menubar: {{ $getShowMenuBar() ? 'true' : 'false' }},
			relative_urls: {{ $getRelativeUrls() ? 'true' : 'false' }},
			remove_script_host: {{ $getRemoveScriptHost() ? 'true' : 'false' }},
			convert_urls: {{ $getConvertUrls() ? 'true' : 'false' }},
			setup: null,
			disabled: @js($isDisabled),
			locale: '{{ app()->getLocale() }}',
			placeholder: @js($getPlaceholder()),
			image_list: {!! $getImageList() !!},
			image_advtab: @js($imageAdvtab()),
			image_description: @js($imageDescription()),
			image_class_list: {!! $getImageClassList() !!},
			custom_configs: @js($getCustomConfigs())
		})"
    >
        @unless($isDisabled())
			<input
                id="{{ $textareaID }}"
				type="hidden"
                x-ref="tinymce"
                placeholder="{{ $getPlaceholder() }}"
            >
        @else
            <div
                x-html="state"
				@style([
                    'max-height: '.$getPreviewMaxHeight().'px' => $getPreviewMaxHeight() > 0,
                    'min-height: '.$getPreviewMinHeight().'px' => $getPreviewMinHeight() > 0,
                ])
                class="block w-full p-3 overflow-y-auto prose transition duration-75 bg-white border border-gray-300 rounded-lg shadow-sm max-w-none opacity-70 dark:prose-invert dark:border-gray-600 dark:bg-gray-700 dark:text-white"
            ></div>
        @endunless
    </div>
</x-dynamic-component>

@pushOnce('scripts')
<script>
// window.addEventListener('beforeunload', (event) => {
//     if (tinymce.activeEditor.isDirty()) {
//         event.preventDefault();
// 		// Included for legacy support, e.g. Chrome/Edge < 119
// 		event.returnValue = '{{ __("Are you sure you want to leave?") }}';
//     }
// });
</script>
@endPushOnce