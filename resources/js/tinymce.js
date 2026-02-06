const allowedMimeTypes = ['image/png', 'image/jpeg', 'image/gif', 'image/webp']

const dispatchFormEvent = (editor, name, detail = {}) => {
	editor.getContainer().closest('form')?.dispatchEvent(
		new CustomEvent(name, {
			composed: true,
			cancelable: true,
			detail,
		}),
	)
};

// const dispatchFormEvent = (editor, name, detail = {}) => {
// 	editor.getContainer().closest('form')?.dispatchEvent(
// 		new CustomEvent(name, {
// 			composed: true,
// 			cancelable: true,
// 			detail,
// 		}),
// 	);
// };

const generateUUID = () => {
	if (typeof crypto !== 'undefined' && typeof crypto.randomUUID === 'function') {
		return crypto.randomUUID();
	}
	return ([1e7] + -1e3 + -4e3 + -8e3 + -1e11).replace(/[018]/g, c =>
		(c ^ (crypto.getRandomValues(new Uint8Array(1))[0] & (15 >> (c / 4)))).toString(16)
	);
};

export default function tinyeditor({
	activePanel,
	state,
	statePath,
	selector,
	plugins,
	external_plugins,
	toolbar,
	text_patterns,
	language = "en",
	language_url = null,
	directionality = "ltr",
	height = null,
	max_height = 0,
	min_height = 100,
	width = null,
	max_width = 0,
	min_width = 400,
	resize = false,
	skin = "oxide",
	content_css = "default",
	content_style = "",
	toolbar_sticky = true,
	toolbar_sticky_offset = 64,
	toolbar_mode = 'sliding',
	toolbar_location = 'auto',
	fixed_toolbar_container_target = null,
	inline = false,
	toolbar_persist = false,
	menubar = false,
	font_size_formats = "",
	fontfamily = "",
	relative_urls = true,
	image_list = null,
	image_advtab = false,
	image_description = false,
	image_class_list = null,
	images_upload_url = null,
	images_upload_base_path = null,
	remove_script_host = true,
	convert_urls = true,
	custom_configs = {},
	setup = null,
	disabled = false,
	locale = "en",
	license_key = "gpl",
	placeholder = null,
	removeImagesEventCallback = null,
	uploadingMessage = "Uploading image...",
	key,
}) {

	let editors = window.filamentTinyEditors || {};

	return {
		activePanel,
		id: null,
		state,
		statePath,
		selector,
		language,
		language_url,
		directionality,
		height,
		max_height,
		min_height,
		width,
		max_width,
		min_width,
		resize,
		skin,
		content_css,
		content_style,
		plugins,
		external_plugins,
		toolbar,
		text_patterns,
		toolbar_sticky,
		menubar,
		relative_urls,
		remove_script_host,
		convert_urls,
		font_size_formats,
		fontfamily,
		setup,
		image_list,
		image_advtab,
		image_description,
		image_class_list,
		images_upload_url,
		images_upload_base_path,
		license_key,
		custom_configs,
		updatedAt: Date.now(),
		disabled,
		locale,
		placeholder,
		removeImagesEventCallback,
		isUploadingFile: false,
		isModalOpen: false,

		init() {
			this.delete();

			if (!this.inline || this.isModalOpen) {
				this.$nextTick(() => {
					this.tryInitializeEditor(this.state || "");
				});
			}

			// this.$watch("state", (newState, oldState) => {
			// 	if (newState === "<p></p>" && newState !== this.editor()?.getContent()) {
			// 		if (this.editor()) {
			// 			this.editor().destroy();
			// 		}
			// 		this.initEditor(newState);
			// 	}

			// 	if (this.editor()?.container && newState !== this.editor()?.getContent()) {
			// 		this.updateEditorContent(newState || "");
			// 		this.putCursorToEnd();
			// 	}
			// });

			this.$watch('state', (value) => {
				if (!this.editor()) return;
				// Skip update if we're currently uploading a file to prevent editor refresh
				if (this.isUploadingFile) return;

				if (value !== this.editor().getContent()) {
					this.updateEditorContent(value || "");
					this.putCursorToEnd();
				}
			});

			window.addEventListener('rich-editor-uploading-file', (event) => {
				if (event.detail.livewireId !== this.$wire.id) return;
				if (event.detail.key !== key) return;
				this.isUploadingFile = true;
				event.stopPropagation();
			});

			window.addEventListener('rich-editor-uploaded-file', (event) => {
				if (event.detail.livewireId !== this.$wire.id) return;
				if (event.detail.key !== key) return;
				this.isUploadingFile = false;
				event.stopPropagation();
			});

			// Listen for Filament modal events
			this.$el.closest('.fi-modal')?.addEventListener('open-tinyeditor-modal', () => {
				this.isModalOpen = true;
				this.$nextTick(() => this.tryInitializeEditor(this.state || ""));
			});

			this.$el.closest('.fi-modal')?.addEventListener('close-tinyeditor-modal', () => {
				this.isModalOpen = false;
				this.delete();
			});

			this.$watch('isModalOpen', (open) => {
				// Skip during file upload to prevent editor refresh
				if (this.isUploadingFile) return;

				if (open) {
					this.$nextTick(() => {
						this.delete();
						this.tryInitializeEditor(this.state || "");
					});
				} else {
					this.delete();
				}
			});

			// Handle Repeater: Re-initialize editor after Livewire morphs DOM
			// This fixes the issue where TinyEditor inside Repeater doesn't show toolbar
			this._reinitOnMorph = () => {
				// Skip reinit during file upload to prevent all editors from refreshing
				if (this.isUploadingFile) return;

				this.$nextTick(() => {
					// Check if the editor element still exists in DOM but editor is not initialized
					const editorElement = document.querySelector(this.selector);
					if (editorElement && !this.editor()) {
						this.tryInitializeEditor(this.state || "");
					}
				});
			};

			// Listen for Livewire v3 morph events
			document.addEventListener('livewire:morph', this._reinitOnMorph);

			// Also handle the case where the element is dynamically added (e.g., repeater item added)
			// Use MutationObserver as a fallback for repeater scenarios
			const repeaterContainer = this.$el.closest('[wire\\:sortable]') || this.$el.closest('.fi-fo-repeater');
			if (repeaterContainer) {
				this._repeaterObserver = new MutationObserver((mutations) => {
					// Skip during file upload to prevent all editors from refreshing
					if (this.isUploadingFile) return;

					this.$nextTick(() => {
						const editorElement = document.querySelector(this.selector);
						if (editorElement && !this.editor()) {
							this.tryInitializeEditor(this.state || "");
						}
					});
				});

				this._repeaterObserver.observe(repeaterContainer, {
					childList: true,
					subtree: true
				});
			}

			// Ensure initialization after Livewire re-renders
			window.addEventListener('livewire:navigated', () => {
				if (this.isModalOpen) {
					this.$nextTick(() => this.tryInitializeEditor(this.state || ""));
				}
			});

			window.filamentTinyEditors = editors;
		},
		editor() {
			return tinymce.get(editors[this.statePath]);
		},
		isInsideRepeater() {
			return this.$el.closest('[wire\\:sortable]') !== null || 
				   this.$el.closest('.fi-fo-repeater') !== null ||
				   this.$el.closest('.fi-fo-builder') !== null;
		},
		tryInitializeEditor(content) {
			// Retry initialization until the target node is available
			if (!document.querySelector(this.selector)) {
				console.warn(`TinyMCE target node ${this.selector} not found. Retrying...`);
				setTimeout(() => this.tryInitializeEditor(content), 100);
				return;
			}

			this.initEditor(content);
		},
		getFileAttachmentUrl: (fileKey) =>
			this.$wire.callSchemaComponentMethod(
				key,
				'getUploadedFileAttachmentTemporaryUrl',
				{
					attachment: fileKey,
				},
			),
		initEditor(content) {
			let _this = this;

			const defaultFontFamilyFormats = "Arial=arial,helvetica,sans-serif; Courier New=courier new,courier,monospace; AkrutiKndPadmini=Akpdmi-n";
			const fontFamilyFormats = fontfamily || defaultFontFamilyFormats;

			const defaultFontSizeFormats = "8pt 10pt 12pt 14pt 16pt 18pt 24pt 36pt 48pt";
			const fontSizeFormats = font_size_formats || defaultFontSizeFormats;

			const tinyConfig = {
				selector: selector,
				language: language,
				language_url: language_url,
				directionality: directionality,
				statusbar: false,
				promotion: false,
				height: height,
				max_height: max_height,
				min_height: min_height,
				width: width,
				max_width: max_width,
				min_width: min_width,
				resize: resize,
				skin: skin,
				content_css: content_css,
				content_style: content_style,
				plugins: plugins,
				external_plugins: external_plugins,
				toolbar: toolbar,
				text_patterns: text_patterns,
				// Disable sticky toolbar inside repeaters to prevent positioning issues
				toolbar_sticky: this.isInsideRepeater() ? false : toolbar_sticky,
				toolbar_sticky_offset: toolbar_sticky_offset,
				toolbar_mode: toolbar_mode,
				toolbar_location: toolbar_location,
				fixed_toolbar_container_target: fixed_toolbar_container_target,
				inline: inline,
				toolbar_persist: toolbar_persist,
				menubar: menubar,
				// Fix z-index issues inside repeaters and modals
				ui_mode: 'split',
				menu: {
					file: {
						title: "File",
						items: "newdocument restoredraft | preview | export print | deleteallconversations",
					},
					edit: {
						title: "Edit",
						items: "undo redo | cut copy paste pastetext | selectall | searchreplace",
					},
					view: {
						title: "View",
						items: "code | visualaid visualchars visualblocks | spellchecker | preview fullscreen | showcomments",
					},
					insert: {
						title: "Insert",
						items: "image link media addcomment pageembed codesample inserttable | charmap emoticons hr | pagebreak nonbreaking anchor tableofcontents | insertdatetime",
					},
					format: {
						title: "Format",
						items: "bold italic underline strikethrough superscript subscript codeformat | styles blocks fontfamily fontsize align lineheight | forecolor backcolor | language | removeformat",
					},
					tools: {
						title: "Tools",
						items: "spellchecker spellcheckerlanguage | a11ycheck code wordcount",
					},
					table: {
						title: "Table",
						items: "inserttable | cell row column | advtablesort | tableprops deletetable",
					},
					help: { title: "Help", items: "help" },
				},
				font_size_formats: fontSizeFormats,
				fontfamily: fontFamilyFormats,
				font_family_formats: fontFamilyFormats,
				relative_urls: relative_urls,
				remove_script_host: remove_script_host,
				convert_urls: convert_urls,
				image_list: image_list,
				image_advtab: image_advtab,
				image_description: image_description,
				image_class_list: image_class_list,
				images_upload_url: images_upload_url,
				images_upload_base_path: images_upload_base_path,
				license_key: license_key,

				...custom_configs,

				setup: function (editor) {
					if (!window.tinySettingsCopy) {
						window.tinySettingsCopy = [];
					}

					if (
						editor.settings &&
						!window.tinySettingsCopy.some(
							(obj) => obj.id === editor.settings.id
						)
					) {
						window.tinySettingsCopy.push(editor.settings);
					}

					editor.on("blur", function (e) {
						_this.updatedAt = Date.now();
						_this.state = editor.getContent();
					});

					editor.on("change", function (e) {
						_this.updatedAt = Date.now();
						_this.state = editor.getContent();
					});

					editor.on("init", function (e) {
						editors[_this.statePath] = editor.id;
						if (content != null) {
							editor.setContent(content);
						}
					});

					editor.on("OpenWindow", function (e) {
						let target = e.target.container.closest(".fi-modal");
						if (target) {
							target.setAttribute("x-trap.noscroll", "false");
						}
					});

					editor.on("CloseWindow", function (e) {
						let target = e.target.container.closest(".fi-modal");
						if (target) {
							target.setAttribute("x-trap.noscroll", "isOpen");
						}
					});

					if (typeof setup === "function") {
						setup(editor);
					}
				},

				removeImagesEventCallback: (imageSrc) => {
					this.$wire.callSchemaComponentMethod(key, 'deleteUploadedImage', { src: imageSrc });
				},

				images_upload_handler: (blobInfo, progress) =>
					new Promise((success, failure) => {
						if (!blobInfo.blob()) {
							failure("No file provided");
							return;
						}

						// Show uploading state
						this.isUploadingFile = true;

						// Dispatch form processing event
						dispatchFormEvent(this.editor(), 'form-processing-started', {
							message: uploadingMessage,
						});

						// Generate unique file key (same as in Tiptap plugin)
						let fileKey = ([1e7] + -1e3 + -4e3 + -8e3 + -1e11).replace(
							/[018]/g, (c) =>
							(c ^ (crypto.getRandomValues(new Uint8Array(1))[0] & (15 >> (c / 4)))).toString(16)
						);
						// let fileKey = generateUUID();

						// Dispatch uploading event
						this.editor().getContainer().dispatchEvent(
							new CustomEvent('rich-editor-uploading-file', {
								bubbles: true,
								detail: {
									key: key,
									livewireId: this.$wire.id,
								},
							})
						);

						// Upload file using Livewire
						this.$wire.upload(
							`componentFileAttachments.${statePath}.${fileKey}`,
							blobInfo.blob(),
							() => {

								this.getFileAttachmentUrl(fileKey)
									.then((tempUrl) => {
										if (!tempUrl) {
											failure("Image upload failed - no URL returned");
											this.isUploadingFile = false;
											dispatchFormEvent(this.editor(), 'form-processing-finished');
											return;
										}

										// Dispatch uploaded event
										this.editor().getContainer().dispatchEvent(
											new CustomEvent('rich-editor-uploaded-file', {
												bubbles: true,
												detail: {
													key: key,
													livewireId: this.$wire.id,
												},
											})
										);

										// Dispatch form processing finished event
										dispatchFormEvent(this.editor(), 'form-processing-finished');
										this.isUploadingFile = false;
										success(tempUrl);

										const editor = this.editor();

										editor.once('SetContent', ({ content, format, paster, selection }) => {
											const imgs = editor.getBody().querySelectorAll('img:not([data-id])');
											if (imgs.length > 0) {
												// Tag the last inserted <img>
												const img = imgs[imgs.length - 1];
												img.setAttribute('data-id', fileKey);
											}
										});
									})
									.catch((error) => {
										console.error('Upload error:', error);
										failure(error);
										this.isUploadingFile = false;
										dispatchFormEvent(this.editor(), 'form-processing-finished');
									});
							},
							(error) => {
								// Upload error
								failure("Upload failed: " + error);
								this.isUploadingFile = false;
								dispatchFormEvent(this.editor(), 'form-processing-finished');
							},
							(event) => {
								// Upload progress
								progress(event.detail.progress * 100); // Convert to percentage
							}
						);
					}),

				init_instance_callback: function (editor) {
					var MutationObserver = window.MutationObserver || window.WebKitMutationObserver || window.MozMutationObserver;

					var isEnabled = removeImagesEventCallback && typeof removeImagesEventCallback === 'function';

					if (!isEnabled) return;

					var observer = new MutationObserver(function (mutations, instance) {
						var addedImages = [];

						mutations.forEach(function (mutationRecord) {
							Array.from(mutationRecord.addedNodes).forEach(function (currentNode) {
								if (currentNode.nodeName === 'IMG' && currentNode.className !== "mce-clonedresizable") {
									if (addedImages.indexOf(currentNode.src) >= 0) return;

									addedImages.push(currentNode.getAttribute("src"));
									return;
								}

								var imgs = currentNode.getElementsByTagName('img');
								Array.from(imgs).forEach(function (img) {
									if (addedImages.indexOf(img.src) >= 0) return;

									addedImages.push(img.getAttribute("src"));
								});
							});
						});

						var removedImages = [];

						mutations.forEach(function (mutationRecord) {
							Array.from(mutationRecord.removedNodes).forEach(function (currentNode) {
								if (currentNode.nodeName === 'IMG' && currentNode.className !== "mce-clonedresizable") {
									if (removedImages.indexOf(currentNode.src) >= 0) return;

									removedImages.push(currentNode.getAttribute("src"));
									return;
								}

								if (currentNode.nodeType === 1) {
									var imgs = currentNode.getElementsByTagName('img');
									Array.from(imgs).forEach(function (img) {
										if (addedImages.indexOf(img.src) >= 0) return;

										addedImages.push(img.getAttribute("src"));
									});
								}
							});
						});

						removedImages.forEach(function (imageSrc) {
							if (addedImages.indexOf(imageSrc) >= 0) return;
							if (removeImagesEventCallback && typeof removeImagesEventCallback === 'function') {
								removeImagesEventCallback(imageSrc);
							}
						});
					});

					observer.observe(editor.getBody(), {
						childList: true,
						subtree: true
					});
				},

				automatic_uploads: true,
			};

			tinymce.init(tinyConfig);
		},
		updateEditorContent(content) {
			this.editor().setContent(content);
		},
		putCursorToEnd() {
			this.editor().selection.select(this.editor().getBody(), true);
			this.editor().selection.collapse(false);
		},
		delete() {
			try {
				if (editors[this.statePath]) {
					this.editor().destroy();
					delete editors[this.statePath];
				}

				// Clean up event listeners
				if (this._reinitOnMorph) {
					document.removeEventListener('livewire:morph', this._reinitOnMorph);
				}
				// Clean up MutationObserver
				if (this._repeaterObserver) {
					this._repeaterObserver.disconnect();
				}

			} catch (error) {
				console.log(editors[this.statePath]);
				console.log(this.editor());
			}
		}
	};
}
