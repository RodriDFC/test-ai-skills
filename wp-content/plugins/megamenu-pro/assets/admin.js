jQuery(document).ready(function($) {

    var media_file_selector = function() {
        $('.mmm_image_selector').not('.loaded').each(function() {

            $(this).addClass('loaded');

            var image_selector = $(this);
            var image_src = image_selector.attr('data-src');
            var input_field = image_selector.attr('data-field');
            var escaped_input_field = input_field.replace(/(:|\.|\[|\])/g,'\\$1');
            var input = $('input#' + escaped_input_field);
            var image = $('<img alt="" />').attr('src', image_src || '');

            if (image_src.length > 0) {
                image_selector.addClass('has_image');
            }

            var deleteBtn = $('<button type="button" class="button button-secondary button-compact button-icon-only mmm_image_selector__btn mmm_image_selector__btn--remove" />')
                .attr('aria-label', megamenu_pro.image_selector_remove_label || 'Remove image')
                .append('<span class="dashicons dashicons-trash" aria-hidden="true"></span>')
                .on('click', function(e) {
                e.stopPropagation();
                image.attr('src', '');
                input.attr('value', '0');
                image_selector.removeClass('has_image');
            });

            var openMediaLibrary = function(e) {
                if (e) {
                    e.preventDefault();
                }

                mm_choose_icon_frame = wp.media.frames.file_frame = wp.media({
                    title: megamenu_pro.file_frame_title,
                    library: {type: 'image'}
                });

                mm_choose_icon_frame.on('select', function() {

                    var selection = mm_choose_icon_frame.state().get('selection');

                    selection.map( function(attachment) {
                        attachment = attachment.toJSON();
                        attachment_id = attachment.id;

                        // SVG and some types may omit `sizes.full` while `sizes` is still an object.
                        var url = attachment.url;
                        if ( attachment.sizes && attachment.sizes.full && attachment.sizes.full.url ) {
                            url = attachment.sizes.full.url;
                        }
                        attachment_url = url;

                        attachment_height = attachment.height;
                        attachment_width = attachment.width;
                    });

                    image.removeAttr( 'width' ).removeAttr( 'height' );
                    image.attr('src', attachment_url);
                    input.attr('value', attachment_id);
                    image_selector.addClass('has_image');

                    if ( attachment_width > 0 && $(".mm_logo_width").is(":visible") ) {
                        $(".mm_logo_width").val(attachment_width).attr('data-src-width', attachment_width);
                    }

                    if ( attachment_height > 0 && $(".mm_logo_height").is(":visible") ) {
                        $(".mm_logo_height").val(40).attr('data-src-height', attachment_height).trigger('change');
                    }
                });

                mm_choose_icon_frame.open();
            };

            var previewBtn = $('<button type="button" class="mmm_image_selector__preview" />')
                .attr('aria-label', megamenu_pro.image_selector_choose_label || 'Choose image')
                .append(image)
                .on('click', openMediaLibrary);

            // Secondary entry point before an image exists (hidden via CSS once `.has_image` is set).
            var chooseBtn = $('<button type="button" class="button button-secondary button-compact button-icon-only mmm_image_selector__btn mmm_image_selector__btn--choose" />')
                .attr('aria-label', megamenu_pro.image_selector_choose_label || 'Choose image')
                .append('<span class="dashicons dashicons-edit" aria-hidden="true"></span>')
                .on('click', openMediaLibrary);

            image_selector.append(previewBtn).append(deleteBtn).append(chooseBtn);
        });
    }

    media_file_selector();

	/** Media File Selector **/
    $(document).on('toggle_block_content_loaded', function() {
        media_file_selector();
    });

    function markToggleHtmlEditorDirty(cm) {
        var $btn = $(cm.getTextArea()).closest('.block').find('.mega-block-save');
        var origLabel = $btn.data('mmm-save-label');
        if (origLabel) $btn.text(origLabel);
        $btn.prop('disabled', false);
    }

    // Sync CodeMirror content to the underlying textarea before the block save serializes the form.
    $(document).on('click', '.toggle-bar-designer .mega-block-save', function() {
        if (typeof window.megamenuCodeEditor !== 'undefined') {
            $('.mm-toggle-html-editor').each(function() {
                window.megamenuCodeEditor.save(this);
            });
        }
    });

    // Initialize (or refresh) the HTML block CodeMirror editor when its panel is opened.
    // Deferred to this event so the textarea is visible when CodeMirror first measures its
    // layout — initializing while hidden (display:none) leaves input non-functional.
    $(document).on('megamenu_toggle_block_opened', function(e, block) {
        if (typeof window.megamenuCodeEditor === 'undefined') {
            return;
        }
        $(block).find('.mm-toggle-html-editor').each(function() {
            window.megamenuCodeEditor.init(this, markToggleHtmlEditorDirty);
            window.megamenuCodeEditor.refresh(this);
        });
    });

    $(document).on('megamenu_content_loaded', function() {
        media_file_selector();
    });

    $(document).on('widget-added', function() {
        media_file_selector();
    });

    $(document).on('megamenu_content_loaded', function() {
        if (typeof wp.codeEditor !== 'undefined') {
            if ($('#codemirror').length) {
                wp.codeEditor.initialize($('#codemirror'), cm_settings);
            }
        }

        $('.mega-color-picker-input').customColorPicker({ 
            defaultColor: '#DDDDDD', 
            showCssVarPalette: false
        });
    });

    // the form is never submitted, so the contents of codemirror never get copied back to the text area.
    // manually copy the contents over when the submit button is clicked.
    $(document).on('click', '.replacements .submit', function() {
        $(document).find('.CodeMirror').each(function(key, value) {
            $('#codemirror').text(value.CodeMirror.getValue());
        });
    });

    // Refresh CodeMirror
    $(document).on('click', '.replacements', function() {
        setTimeout( function() {
            $(document).find('.CodeMirror').each(function(key, value) {
                value.CodeMirror.refresh();
            });
        }, 160);
    });

    $(document).on('change', '#mega_replacement_type', function() {
        setTimeout( function() {
            $(document).find('.CodeMirror').each(function(key, value) {
                value.CodeMirror.refresh();
            });
        }, 160);
    });

    $(document).on("change", "select#mega_replacement_mode", function() {
        var select = $(this);
        var selected = $(this).val();
        select.next().children().hide();
        select.next().children('.' + selected).show();
    });

    $(document).on("change", 'select#mega_replacement_type', function() {
        var select = $(this);
        var selected = $(this).val();
        $(".replacements table tr").not(".type").hide();
        $(".replacements table tr." + selected).show();
    });

    $(document).on('keyup keypress blur change mousedown', '.mm_logo_width', function() {
        var src_height = $(".mm_logo_height").attr('data-src-height');
        var src_width = $(".mm_logo_width").attr('data-src-width');

        if (src_width == 0 || src_height == 0) {
            return;
        }

        var configured_width = $(this).val();
        var ratio = src_height / src_width;
        $(".mm_logo_height").val(parseInt(configured_width * ratio));
    });

    $(document).on('keyup keypress blur change mousedown', '.mm_logo_height', function() {
        var src_height = $(".mm_logo_height").attr('data-src-height');
        var src_width = $(".mm_logo_width").attr('data-src-width');

        if (src_width == 0 || src_height == 0) {
            return;
        }

        var configured_height = $(this).val();
        var ratio = src_width / src_height;
        $(".mm_logo_width").val(parseInt(configured_height * ratio));
    });


	/** Roles **/
    $(document).on('change', '#mm_roles select', function() {
        var option = $(this);

        if (option.val() == 'by_role') {
            $('#mm_roles input[type=checkbox]').removeAttr('disabled');
        } else {
            $('#mm_roles input[type=checkbox]').attr('disabled', 'disabled');
        }
    });

    /** Style Overrides **/
    $(document).on('change', '.override_toggle_enabled', function() {
        var checkbox = $(this);
        var checked = checkbox.is(":checked");

        var inputs = checkbox.parent().siblings().find('input, select');

        inputs.each(function() {

            var name = $(this).attr('name');

            if (checked) {
                name = name.replace('disabled', 'enabled');
            } else {
                name = name.replace('enabled', 'disabled');
            }

            $(this).attr('name', name);

        });

        var parent = checkbox.parent().parent();

        parent.toggleClass('mega-enabled', 'mega-disabled');
    });

    $(document).on('blur', '#mm_custom_styles .mega-enabled .mega-value input[type=text]', function() {
        $( ".mega-enabled .mega-value input[type=text]", '#mm_custom_styles').each(function( index ) {
            if ($(this).val().length === 0 ) {
                $(this).val('0px');
            }
        });
    });

    $(document).on('change', '.megamenu_orientation_select', function() {
        var accordion_behaviour_row = $(this).parent().parent().parent().children('tr.megamenu_accordion_behaviour');

        if ( $(this).val() == 'accordion' ) {
            accordion_behaviour_row.show();
        } else {
            accordion_behaviour_row.hide();
        }
    });

});