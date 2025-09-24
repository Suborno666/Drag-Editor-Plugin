jQuery(document).ready(function($) {
    console.log("Dynamic Editor loaded with drag & drop - ready for action!");
    
    // Template configurations
    const templates = {
        header: {
            code: '$html_field->text_field()',
            shortcode: '[header_text]Your Header Text[/header_text]',
            placeholder: 'Your Header Text Here'
        },
        textblock: {
            code: '$html_field->text_block()',
            shortcode: '[text_block]Your paragraph text...[/text_block]',
            placeholder: 'Your paragraph text goes here...'
        },
        button: {
            code: '$html_field->button_field()',
            shortcode: '[button text="Click Me" url="#"]',
            placeholder: '[Button: Click Me]'
        },
        image: {
            code: '$html_field->image_field()',
            shortcode: '[image src="your-image.jpg" alt="Your Image"]',
            placeholder: '[Image: your-image.jpg]'
        }
    };
    
    let draggedTemplate = null;
    
    // Add drop indicator to editor
    $('#my-editor-container').append('<div class="drop-indicator">Drop template here</div>');
    
    // Add code preview toggle
    $('#my-editor-container').append(
        '<button type="button" class="code-toggle">Show Generated Code</button>' +
        '<div class="code-preview" id="codePreview"></div>'
    );
    
    // Handle template dragging
    $('.template-item').on('dragstart', function(e) {
        draggedTemplate = $(this).data('template');
        $(this).addClass('dragging');
        console.log('Dragging template:', draggedTemplate);
    });
    
    $('.template-item').on('dragend', function(e) {
        $(this).removeClass('dragging');
        draggedTemplate = null;
    });
    
    // Handle editor drop zone
    const $editorContainer = $('#my-editor-container');
    const $textarea = $('#my-custom-content');
    
    $editorContainer.on('dragover', function(e) {
        e.preventDefault();
        $(this).addClass('drag-over');
    });
    
    $editorContainer.on('dragleave', function(e) {
        if (!$(this)[0].contains(e.relatedTarget)) {
            $(this).removeClass('drag-over');
        }
    });
    
    $editorContainer.on('drop', function(e) {
        e.preventDefault();
        $(this).removeClass('drag-over');
        
        if (draggedTemplate && templates[draggedTemplate]) {
            insertTemplate(draggedTemplate);
        }
    });
    
    // Insert template into editor
    function insertTemplate(templateKey) {
        const template = templates[templateKey];
        const $textarea = $('#my-custom-content');
        const cursorPos = $textarea[0].selectionStart;
        const textBefore = $textarea.val().substring(0, cursorPos);
        const textAfter = $textarea.val().substring(cursorPos);
        
        // Insert the shortcode at cursor position
        const newContent = textBefore + template.shortcode + '\n' + textAfter;
        $textarea.val(newContent);
        
        // Position cursor after inserted content
        const newCursorPos = cursorPos + template.shortcode.length + 1;
        $textarea[0].setSelectionRange(newCursorPos, newCursorPos);
        $textarea.focus();
        
        // Add insertion animation
        $textarea.addClass('template-inserted');
        setTimeout(() => {
            $textarea.removeClass('template-inserted');
        }, 500);
        
        // Update code preview if it's visible
        if ($('#codePreview').hasClass('active')) {
            updateCodePreview();
        }
        
        console.log('Inserted template:', templateKey);
        
        // Show a nice notification
        showNotification('Template inserted! 🎉');
    }
    
    // Code preview toggle
    $('.code-toggle').on('click', function() {
        const $preview = $('#codePreview');
        const $button = $(this);
        
        if ($preview.hasClass('active')) {
            $preview.removeClass('active');
            $button.text('Show Generated Code');
        } else {
            $preview.addClass('active');
            $button.text('Hide Generated Code');
            updateCodePreview();
        }
    });
    
    // Update code preview
    function updateCodePreview() {
        const content = $('#my-custom-content').val();
        let phpCode = '<?php\n// Generated PHP code:\n\n';
        let foundShortcodes = false;
        
        // Parse shortcodes and convert to PHP
        for (let key in templates) {
            const template = templates[key];
            const shortcodeRegex = new RegExp('\\[' + key.replace('textblock', 'text_block') + '[^\\]]*\\].*?\\[\\/' + key.replace('textblock', 'text_block') + '\\]', 'g');
            const simpleShortcodeRegex = new RegExp('\\[' + key + '[^\\]]*\\]', 'g');
            
            if (content.match(shortcodeRegex) || content.match(simpleShortcodeRegex)) {
                phpCode += `echo ${template.code};\n`;
                foundShortcodes = true;
            }
        }
        
        if (!foundShortcodes) {
            phpCode += '// No templates found in content\n';
        }
        
        phpCode += '\n?>\n\n';
        phpCode += '<!-- Shortcodes in content: -->\n';
        phpCode += content || '// No content yet';
        
        $('#codePreview').text(phpCode);
    }
    
    // Template collapse functionality
    $('.collapse-btn').on('click', function() {
        const $list = $('#templatesList');
        const $btn = $(this);
        
        if ($list.is(':visible')) {
            $list.hide();
            $btn.text('▶');
        } else {
            $list.show();
            $btn.text('▼');
        }
    });
    
    // Show notification
    function showNotification(message) {
        // Remove existing notification
        $('.custom-notification').remove();
        
        // Create new notification
        const $notification = $('<div class="custom-notification">' + message + '</div>');
        $notification.css({
            position: 'fixed',
            top: '50px',
            right: '20px',
            background: '#00a32a',
            color: 'white',
            padding: '10px 15px',
            borderRadius: '4px',
            fontSize: '14px',
            zIndex: '9999',
            boxShadow: '0 2px 5px rgba(0,0,0,0.2)'
        });
        
        $('body').append($notification);
        
        // Fade out after 2 seconds
        setTimeout(() => {
            $notification.fadeOut(300, function() {
                $(this).remove();
            });
        }, 2000);
    }
    
    // Editor focus enhancement
    $('#my-custom-content').on('focus', function() {
        console.log("Editor focused - ready to receive templates!");
        $(this).closest('#my-editor-container').addClass('editor-focused');
    });
    
    $('#my-custom-content').on('blur', function() {
        $(this).closest('#my-editor-container').removeClass('editor-focused');
    });
    
    // Auto-update code preview on content change
    $('#my-custom-content').on('input', function() {
        if ($('#codePreview').hasClass('active')) {
            clearTimeout(window.updateTimeout);
            window.updateTimeout = setTimeout(updateCodePreview, 500);
        }
    });
});