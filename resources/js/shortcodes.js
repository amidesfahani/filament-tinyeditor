// TinyMCE Shortcodes Plugin
tinymce.PluginManager.add('shortcodes', function(editor) {
    // Get shortcodes from TinyMCE init config (using getParam for unregistered options)
    var shortcodes = editor.getParam('shortcodes_data', []);
    var shortcodesLabel = editor.getParam('shortcodes_label', 'Shortcodes');

    if (!shortcodes || shortcodes.length === 0) {
        console.warn('TinyMCE Shortcodes: No shortcodes configured');
        return;
    }

    // Build lookup map for converting shortcodes to labels
    var shortcodeMap = {};
    shortcodes.forEach(function(s) {
        shortcodeMap[s.value] = s.text;
    });

    // Create shortcode tag HTML
    function createShortcodeTag(value, text) {
        return '<span class="shortcode-tag" data-shortcode="' + value + '" contenteditable="false">' + text + '</span>';
    }

    // Convert {{ shortcode }} to visual tags when content is set
    editor.on('BeforeSetContent', function(e) {
        if (e.content) {
            e.content = e.content.replace(/\{\{\s*(\w+)\s*\}\}/g, function(match, code) {
                var label = shortcodeMap[code];
                if (label) {
                    return createShortcodeTag(code, label);
                }
                return match;
            });
        }
    });

    // Convert visual tags back to {{ shortcode }} when getting content
    editor.on('GetContent', function(e) {
        if (e.content) {
            // Convert span tags back to shortcode format
            e.content = e.content.replace(/<span[^>]*class="shortcode-tag"[^>]*data-shortcode="(\w+)"[^>]*>[^<]*<\/span>/gi, function(match, code) {
                return '{{ ' + code + ' }}';
            });
        }
    });

    // Add CSS styles for shortcode tags
    editor.on('init', function() {
        var css = '.shortcode-tag { ' +
            'display: inline-block; ' +
            'background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); ' +
            'color: white; ' +
            'padding: 2px 8px; ' +
            'border-radius: 12px; ' +
            'font-size: 12px; ' +
            'font-weight: 500; ' +
            'margin: 0 2px; ' +
            'cursor: default; ' +
            'user-select: none; ' +
            '-webkit-user-select: none; ' +
            'vertical-align: baseline; ' +
        '}';

        var head = editor.getDoc().head;
        var style = editor.getDoc().createElement('style');
        style.type = 'text/css';
        style.appendChild(editor.getDoc().createTextNode(css));
        head.appendChild(style);
    });

    // Register the menu button
    editor.ui.registry.addMenuButton('shortcodes', {
        text: shortcodesLabel,
        icon: 'bookmark',
        tooltip: shortcodesLabel,
        fetch: function(callback) {
            var items = shortcodes.map(function(shortcode) {
                return {
                    type: 'menuitem',
                    text: shortcode.text,
                    onAction: function() {
                        editor.insertContent(createShortcodeTag(shortcode.value, shortcode.text));
                    }
                };
            });
            callback(items);
        }
    });

    return {
        getMetadata: function() {
            return {
                name: 'Shortcodes Plugin',
                url: 'https://github.com/amidesfahani/filament-tinyeditor'
            };
        }
    };
});
