<x-dynamic-component
    :component="$getFieldWrapperView()"
    :field="$field"
    class="relative z-0"
>
	@php
		$textareaID = 'tiny-editor-' . str_replace(['.', '#', '$'], '', $getId());
	@endphp

    <div
		x-data="{ state: $wire.{{ $applyStateBindingModifiers("\$entangle('{$getStatePath()}')") }}, initialized: false }"
		x-load-css="[@js(\Filament\Support\Facades\FilamentAsset::getStyleHref('tiny-css', package: 'amidesfahani/filament-tinyeditor'))]"
        x-load-js="[@js(\Filament\Support\Facades\FilamentAsset::getScriptSrc($getLanguageId(), package: 'amidesfahani/filament-tinyeditor'))]"
        x-init="(() => {
            $nextTick(() => {
				tinymce.init({
					selector: '#{{ $textareaID }}',
					language: '{{ $getInterfaceLanguage() }}',
					language_url: 'https://cdn.jsdelivr.net/npm/tinymce-i18n@23.10.9/langs6/{{ $getInterfaceLanguage() }}.min.js',
					directionality: '{{ $getDirection() }}',
					
					toolbar_sticky: {{ $getToolbarSticky() ? 'true' : 'false' }},
					toolbar_sticky_offset: 64,
					statusbar: false,
					promotion: false,
		
					max_height: {{ $getMaxHeight() }},
					min_height: {{ $getMinHeight() }},

					@if($darkMode() == 'media')
					skin: (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'oxide-dark' : ''),
					content_css: (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : ''),
					@elseif($darkMode() == 'class')
					skin: (document.querySelector('html').getAttribute('class').includes('dark') ? 'oxide-dark' : 'oxide'),
					content_css: (document.querySelector('html').getAttribute('class').includes('dark') ? 'dark' : 'default'),
					@elseif($darkMode() == 'force')
					skin: 'oxide-dark',
					content_css: 'dark',
					@elseif($darkMode() == false)
					skin: 'oxide',
					content_css: 'default',
					@else
					skin: (window.matchMedia('(prefers-color-scheme: dark)').matches || document.querySelector('html').getAttribute('class').includes('dark') ? 'oxide-dark' : 'oxide'),
					content_css: (window.matchMedia('(prefers-color-scheme: dark)').matches || document.querySelector('html').getAttribute('class').includes('dark') ? 'dark' : 'default'),
					@endif

					plugins: '{{ $getPlugins() }}',
					toolbar: '{{ $getToolbar() }}',
					toolbar_mode: 'sliding',

					templates: '{{ $getTemplates() }}',
		
					menubar: {{ $getShowMenuBar() ? 'true' : 'false' }},
					menu: {
						file: { title: 'File', items: 'newdocument restoredraft | preview | export print | deleteallconversations' },
						edit: { title: 'Edit', items: 'undo redo | cut copy paste pastetext | selectall | searchreplace' },
						view: { title: 'View', items: 'code | visualaid visualchars visualblocks | spellchecker | preview fullscreen | showcomments' },
						insert: { title: 'Insert', items: 'image link media addcomment pageembed template codesample inserttable | charmap emoticons hr | pagebreak nonbreaking anchor tableofcontents | insertdatetime' },
						format: { title: 'Format', items: 'bold italic underline strikethrough superscript subscript codeformat | styles blocks fontfamily fontsize align lineheight | forecolor backcolor | language | removeformat' },
						tools: { title: 'Tools', items: 'spellchecker spellcheckerlanguage | a11ycheck code wordcount' },
						table: { title: 'Table', items: 'inserttable | cell row column | advtablesort | tableprops deletetable' },
						help: { title: 'Help', items: 'help' }
					},
		
					relative_urls: {{ $getRelativeUrls() ? 'true' : 'false' }},
					remove_script_host: {{ $getRemoveScriptHost() ? 'true' : 'false' }},
					convert_urls: {{ $getConvertUrls() ? 'true' : 'false' }},
		
					setup: function (editor) {
						if(!window.tinySettingsCopy) {
                            window.tinySettingsCopy = [];
                        }

                        if (!window.tinySettingsCopy.some(obj => obj.id === editor.settings.id)) {
                            window.tinySettingsCopy.push(editor.settings);
                        }

						editor.on('blur', function(e) {
							state = editor.getContent()
						});
		
						editor.on('init', function(e) {
							if (state != null) {
							    editor.setContent(state)
							}
						});
		
						editor.on('OpenWindow', function(e) {
							target = e.target.container.closest('.fi-modal')
							if (target) target.setAttribute('x-trap.noscroll', 'false')
						});
		
						editor.on('CloseWindow', function(e) {
							target = e.target.container.closest('.fi-modal')
							if (target) target.setAttribute('x-trap.noscroll', 'isOpen')
						});

						function putCursorToEnd() {
                            editor.selection.select(editor.getBody(), true);
                            editor.selection.collapse(false);
                        }

						$watch('state', function(newstate) {
                            if (editor.container && newstate !== editor.getContent()) {
                                editor.resetContent(newstate || '');
                                putCursorToEnd();
                            }
                        });
					},

					images_upload_handler: (blobInfo, progress) => new Promise((resolve, reject) => {
                        if (!blobInfo.blob()) return

						const finishCallback = () => {
							$wire.getFormComponentFileAttachmentUrl('{{ $getStatePath() }}').then((url) => {
								if (!url) {
									reject('error')
									return
								}
								resolve(url)
							})
						}

						const errorCallback = () => {}

						const progressCallback = (e) => {
							progress(e.detail.progress)
						}

						$wire.upload(`componentFileAttachments.{{ $getStatePath() }}`, blobInfo.blob(), finishCallback, errorCallback, progressCallback)
                    }),

					automatic_uploads: true,
					{{ $getCustomConfigs() }}
				});
            });
        })()"
        x-cloak
        wire:ignore
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
                class="block w-full max-w-none rounded-lg border border-gray-300 bg-white p-3 opacity-70 shadow-sm transition duration-75 prose dark:prose-invert dark:border-gray-600 dark:bg-gray-700 dark:text-white"
            ></div>
        @endunless
    </div>
</x-dynamic-component>

@pushOnce('scripts')
<script>
window.addEventListener('beforeunload', (event) => {
    if (tinymce.activeEditor.isDirty()) {
        event.preventDefault();
		// Included for legacy support, e.g. Chrome/Edge < 119
		event.returnValue = '{{ __("Are you sure you want to leave?") }}';
    }
});
</script>
@endPushOnce