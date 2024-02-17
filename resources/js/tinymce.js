export default function tinyeditor({
	state,
	statePath,
	selector,
	plugins,
	toolbar,
	language = "en",
	language_url = null,
	directionality = "ltr",
	max_height = 0,
	min_height = 500,
	skin = "oxide",
	content_css = "default",
	toolbar_sticky = false,
	templates = "",
	menubar = false,
	relative_urls = true,
	image_list = null,
	image_advtab = false,
	image_description = false,
	image_class_list = null,
	remove_script_host = true,
	convert_urls = true,
	custom_configs = {},
	setup = null,
	disabled = false,
	locale = "en",
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
		max_height: max_height,
		min_height: min_height,
		skin: skin,
		content_css: content_css,
		plugins: plugins,
		toolbar: toolbar,
		toolbar_sticky: toolbar_sticky,
		templates: templates,
		menubar: menubar,
		relative_urls: relative_urls,
		remove_script_host: remove_script_host,
		convert_urls: convert_urls,
		setup: setup,
		image_list: image_list,
		image_advtab: image_advtab,
		image_description: image_description,
		image_class_list: image_class_list,
		custom_configs: custom_configs,
		updatedAt: Date.now(),
		disabled,
		locale: locale,
		init() {
			this.initEditor(state.initialValue);

			window.filamentTinyEditors = editors;

			this.$watch("state", (newState, oldState) => {
				if (newState === "<p></p>" && newState !== this.editor().getContent()) {
					editors[this.statePath].destroy();
					this.initEditor(newState);
				}

				if (this.editor().container && newState !== this.editor().getContent()) {
					this.editor().resetContent(newState || "");
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

			tinymce.init({
				selector: selector,
				language: language,
				language_url: language_url,
				directionality: directionality,
				statusbar: false,
				promotion: false,
				max_height: max_height,
				min_height: min_height,
				skin: skin,
				content_css: content_css,
				plugins: plugins,
				toolbar: toolbar,
				toolbar_sticky: toolbar_sticky,
				toolbar_sticky_offset: 64,
				toolbar_mode: "sliding",
				templates: templates,
				menubar: menubar,
				menu: {
					file: { title: "File", items: "newdocument restoredraft | preview | export print | deleteallconversations" },
					edit: { title: "Edit", items: "undo redo | cut copy paste pastetext | selectall | searchreplace" },
					view: { title: "View", items: "code | visualaid visualchars visualblocks | spellchecker | preview fullscreen | showcomments" },
					insert: { title: "Insert", items: "image link media addcomment pageembed template codesample inserttable | charmap emoticons hr | pagebreak nonbreaking anchor tableofcontents | insertdatetime" },
					format: { title: "Format", items: "bold italic underline strikethrough superscript subscript codeformat | styles blocks fontfamily fontsize align lineheight | forecolor backcolor | language | removeformat" },
					tools: { title: "Tools", items: "spellchecker spellcheckerlanguage | a11ycheck code wordcount" },
					table: { title: "Table", items: "inserttable | cell row column | advtablesort | tableprops deletetable" },
					help: { title: "Help", items: "help" },
				},
				relative_urls: relative_urls,
				remove_script_host: remove_script_host,
				convert_urls: convert_urls,
				image_list: image_list,
				image_advtab: image_advtab,
				image_description: image_description,
				image_class_list: image_class_list,
				...custom_configs,

				setup: function (editor) {
					if (!window.tinySettingsCopy) {
						window.tinySettingsCopy = [];
					}

					if (editor.settings && !window.tinySettingsCopy.some((obj) => obj.id === editor.settings.id)) {
						window.tinySettingsCopy.push(editor.settings);
					}

					editor.on("blur", function (e) {
						_this.updatedAt = Date.now();
						_this.state = editor.getContent();
					});

					editor.on("init", function (e) {
						editors[_this.statePath] = editor.id;
						if (content != null) {
							editor.setContent(content);
						}
					});

					if (typeof setup === "function") {
						setup(editor);
					}
				},

				images_upload_handler: (blobInfo, progress) =>
					new Promise((resolve, reject) => {
						if (!blobInfo.blob()) return;

						const finishCallback = () => {
							$wire.getFormComponentFileAttachmentUrl(statePath).then((url) => {
								if (!url) {
									reject("error");
									return;
								}
								resolve(url);
							});
						};

						const errorCallback = () => {};

						const progressCallback = (e) => {
							progress(e.detail.progress);
						};

						$wire.upload(`componentFileAttachments.${statePath}`, blobInfo.blob(), finishCallback, errorCallback, progressCallback);
					}),

				automatic_uploads: true,
			});
		},
		updateEditorContent(content) {
			this.editor().setContent(content);
		},
		putCursorToEnd() {
			this.editor().selection.select(this.editor().getBody(), true);
			this.editor().selection.collapse(false);
		},
	};
}
