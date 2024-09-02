export default function tinyeditor({
	state,
	statePath,
	selector,
	plugins,
	external_plugins,
	toolbar,
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
	toolbar_sticky = true,
	toolbar_sticky_offset = 64,
	toolbar_mode = 'sliding',
	toolbar_location = 'auto',
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
}) {
	let editors = window.filamentTinyEditors || {};
	return {
		id: null,
		state: state,
		statePath: statePath,
		selector: selector,
		language: language,
		language_url: language_url,
		directionality: directionality,
		height: height,
		max_height: max_height,
		min_height: min_height,
		width: width,
		max_width: max_width,
		min_width: min_width,
		resize: resize,
		skin: skin,
		content_css: content_css,
		plugins: plugins,
		external_plugins: external_plugins,
		toolbar: toolbar,
		toolbar_sticky: toolbar_sticky,
		menubar: menubar,
		relative_urls: relative_urls,
		remove_script_host: remove_script_host,
		convert_urls: convert_urls,
		font_size_formats: font_size_formats,
		fontfamily: fontfamily,
		setup: setup,
		image_list: image_list,
		image_advtab: image_advtab,
		image_description: image_description,
		image_class_list: image_class_list,
		images_upload_url: images_upload_url,
		images_upload_base_path: images_upload_base_path,
		license_key: license_key,
		custom_configs: custom_configs,
		updatedAt: Date.now(),
		disabled,
		locale: locale,
		init() {
			this.delete();

			this.initEditor(state.initialValue);

			window.filamentTinyEditors = editors;

			this.$watch("state", (newState, oldState) => {
				if (newState === "<p></p>" && newState !== this.editor()?.getContent()) {
					this.delete();
					this.initEditor(newState);
				}

				if (
					this.editor()?.container &&
					newState !== this.editor()?.getContent()
				) {
					this.updateEditorContent(newState || "");
					this.putCursorToEnd();
			}
			});
		},
		editor() {
			return tinymce.get(editors[this.statePath]);
		},
		initEditor(content) {
			let _this = this;
			let $wire = this.$wire;

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
				plugins: plugins,
				external_plugins: external_plugins,
				toolbar: toolbar,
				toolbar_sticky: toolbar_sticky,
				toolbar_sticky_offset: toolbar_sticky_offset,
				toolbar_mode: toolbar_mode,
				toolbar_location: toolbar_location,
				inline: inline,
				toolbar_persist: toolbar_persist,
				menubar: menubar,
				menu: {
					file: {
						title: "File",
						items:
							"newdocument restoredraft | preview | export print | deleteallconversations",
					},
					edit: {
						title: "Edit",
						items:
							"undo redo | cut copy paste pastetext | selectall | searchreplace",
					},
					view: {
						title: "View",
						items:
							"code | visualaid visualchars visualblocks | spellchecker | preview fullscreen | showcomments",
					},
					insert: {
						title: "Insert",
						items:
							"image link media addcomment pageembed codesample inserttable | charmap emoticons hr | pagebreak nonbreaking anchor tableofcontents | insertdatetime",
					},
					format: {
						title: "Format",
						items:
							"bold italic underline strikethrough superscript subscript codeformat | styles blocks fontfamily fontsize align lineheight | forecolor backcolor | language | removeformat",
					},
					tools: {
						title: "Tools",
						items:
							"spellchecker spellcheckerlanguage | a11ycheck code wordcount",
					},
					table: {
						title: "Table",
						items:
							"inserttable | cell row column | advtablesort | tableprops deletetable",
					},
					help: { title: "Help", items: "help" },
				},
				font_size_formats: font_size_formats,
				fontfamily: fontfamily,
				font_family_formats: fontfamily,
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

					editor.on("OpenWindow", function(e) {
						let target = e.target.container.closest(".fi-modal");
						if (target){
							target.setAttribute("x-trap.noscroll", "false");
						}
					});

					editor.on("CloseWindow", function(e) {
						let target = e.target.container.closest(".fi-modal");
						if (target){
							target.setAttribute("x-trap.noscroll", "isOpen");
						}
					});

					if (typeof setup === "function") {
						setup(editor);
					}
				},

				images_upload_handler: (blobInfo, progress) =>
					new Promise((success, failure) => {
						if (!blobInfo.blob()) return;

						const pathJoin = (path1, path2) => {
							if (path1) {
							  return path1.replace(/\/$/, '') + '/' + path2.replace(/^\//, '');
							}
							return path2;
						};

						const finishCallback = () => {
							$wire.getFormComponentFileAttachmentUrl(statePath).then((url) => {
								if (!url) {
									failure("Image upload failed");
									return;
								}
								success(pathJoin(images_upload_base_path, url));
							});
						};

						const errorCallback = () => {};

						const progressCallback = (e) => {
							progress(e.detail.progress);
						};

						$wire.upload(
							`componentFileAttachments.${statePath}`,
							blobInfo.blob(),
							finishCallback,
							errorCallback,
							progressCallback
						);
					}),

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
		delete()	{
			if (editors[this.statePath]) {
				this.editor().destroy();
				delete	editors[this.statePath];
			}
		}
	};
}
