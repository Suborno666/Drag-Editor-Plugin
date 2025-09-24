<?php 
include __DIR__.'/html-snippets.php';
$html_field = new html_Fields();
?>

<div class="templates-panel">
    <div class="templates-header">
        <span>Templates</span>
        <button class="collapse-btn" type="button">▼</button>
    </div>
    
    <div class="templates-list" id="templatesList">
        <!-- Header Template -->
        <div class="template-item" draggable="true" data-template="header" data-method="text_field">
            <div class="template-preview">
                <?php echo $html_field->text_field() ?>
            </div>
            <div class="template-info">
                <strong>Header Text</strong>
                <span class="template-desc">H1 Header Element</span>
            </div>
        </div>
        
        <!-- Text Block Template -->
        <div class="template-item" draggable="true" data-template="textblock" data-method="text_block">
            <div class="template-preview">
                <div class="text-block">
                    <p>Sample paragraph text...</p>
                </div>
            </div>
            <div class="template-info">
                <strong>Text Block</strong>
                <span class="template-desc">Paragraph Content</span>
            </div>
        </div>
        
        <!-- Button Template -->
        <div class="template-item" draggable="true" data-template="button" data-method="button_field">
            <div class="template-preview">
                <div class="button-element">
                    <button type="button" class="btn-preview">Click Me</button>
                </div>
            </div>
            <div class="template-info">
                <strong>Button</strong>
                <span class="template-desc">Call to Action</span>
            </div>
        </div>
        
        <!-- Image Template -->
        <div class="template-item" draggable="true" data-template="image" data-method="image_field">
            <div class="template-preview">
                <div class="image-placeholder">🖼️ Image</div>
            </div>
            <div class="template-info">
                <strong>Image</strong>
                <span class="template-desc">Image Element</span>
            </div>
        </div>
    </div>
</div>

<style>
.templates-panel {
    background: #fff;
    border: 1px solid #c3c4c7;
    border-radius: 4px;
    overflow: hidden;
}

.templates-header {
    background: #f6f7f7;
    padding: 12px 15px;
    border-bottom: 1px solid #c3c4c7;
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-weight: 600;
}

.collapse-btn {
    background: none;
    border: none;
    cursor: pointer;
    color: #646970;
    font-size: 12px;
    padding: 0;
}

.templates-list {
    padding: 10px;
}

.template-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 12px;
    margin-bottom: 8px;
    background: #f9f9f9;
    border: 1px solid #dcdcde;
    border-radius: 4px;
    cursor: grab;
    transition: all 0.2s ease;
}

.template-item:hover {
    background: #f0f0f1;
    border-color: #0073aa;
    transform: translateY(-1px);
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}

.template-item:active {
    cursor: grabbing;
}

.template-item.dragging {
    opacity: 0.7;
    transform: rotate(3deg) scale(1.05);
}

.template-preview {
    width: 40px;
    height: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #fff;
    border: 1px solid #ddd;
    border-radius: 3px;
    font-size: 10px;
    overflow: hidden;
}

.template-preview .Header-Text h1 {
    font-size: 8px;
    margin: 0;
    color: #333;
}

.template-preview .text-block p {
    font-size: 6px;
    margin: 0;
    line-height: 1.2;
}

.btn-preview {
    font-size: 6px;
    padding: 2px 4px;
    background: #0073aa;
    color: #fff;
    border: none;
    border-radius: 2px;
}

.image-placeholder {
    font-size: 12px;
}

.template-info {
    flex: 1;
    display: flex;
    flex-direction: column;
}

.template-info strong {
    font-size: 12px;
    color: #1d2327;
}

.template-desc {
    font-size: 11px;
    color: #646970;
    margin-top: 2px;
}
</style>