<?php

class html_Fields{
    function __construct() {
        add_shortcode('header_text', array($this, 'header_shortcode'));
        add_shortcode('text_block', array($this, 'text_block_shortcode'));
        add_shortcode('button', array($this, 'button_shortcode'));
        add_shortcode('image', array($this, 'image_shortcode'));
    }
    
    function text_field(){
        ob_start();
        ?>
        <div class='Header-Text'>
            <h1>Your Header Text Here</h1>
        </div>
        <?php
        $content = ob_get_clean();
        return $content;
    }
    
    function text_block(){
        ob_start();
        ?>
        <div class='text-block'>
            <p>Your paragraph text goes here. This is where you can add longer content, descriptions, or any other text-based content you need.</p>
        </div>
        <?php
        $content = ob_get_clean();
        return $content;
    }
    
    function button_field(){
        ob_start();
        ?>
        <div class='button-element'>
            <a href="#" class="btn btn-primary">Click Me</a>
        </div>
        <?php
        $content = ob_get_clean();
        return $content;
    }
    
    function image_field(){
        ob_start();
        ?>
        <div class='image-element'>
            <img src="https://via.placeholder.com/400x200/0073aa/ffffff?text=Your+Image" alt="Placeholder Image" style="max-width: 100%; height: auto;">
        </div>
        <?php
        $content = ob_get_clean();
        return $content;
    }
    
    // Shortcode handlers
    function header_shortcode($atts, $content = null) {
        $atts = shortcode_atts(array(
            'class' => 'Header-Text',
            'tag' => 'h1'
        ), $atts);
        
        ob_start();
        ?>
        <div class='<?php echo esc_attr($atts['class']); ?>'>
            <?php echo '<' . $atts['tag'] . '>' . esc_html($content) . '</' . $atts['tag'] . '>'; ?>
        </div>
        <?php
        return ob_get_clean();
    }
    
    function text_block_shortcode($atts, $content = null) {
        $atts = shortcode_atts(array(
            'class' => 'text-block'
        ), $atts);
        
        ob_start();
        ?>
        <div class='<?php echo esc_attr($atts['class']); ?>'>
            <p><?php echo wp_kses_post($content); ?></p>
        </div>
        <?php
        return ob_get_clean();
    }
    
    function button_shortcode($atts, $content = null) {
        $atts = shortcode_atts(array(
            'text' => 'Click Me',
            'url' => '#',
            'class' => 'btn btn-primary',
            'target' => '_self'
        ), $atts);
        
        ob_start();
        ?>
        <div class='button-element'>
            <a href="<?php echo esc_url($atts['url']); ?>" 
               class="<?php echo esc_attr($atts['class']); ?>" 
               target="<?php echo esc_attr($atts['target']); ?>">
                <?php echo esc_html($atts['text']); ?>
            </a>
        </div>
        <?php
        return ob_get_clean();
    }
    
    function image_shortcode($atts, $content = null) {
        $atts = shortcode_atts(array(
            'src' => 'https://via.placeholder.com/400x200',
            'alt' => 'Image',
            'class' => 'image-element',
            'width' => '',
            'height' => ''
        ), $atts);
        
        $style = '';
        if ($atts['width']) $style .= 'width: ' . $atts['width'] . 'px; ';
        if ($atts['height']) $style .= 'height: ' . $atts['height'] . 'px; ';
        
        ob_start();
        ?>
        <div class='<?php echo esc_attr($atts['class']); ?>'>
            <img src="<?php echo esc_url($atts['src']); ?>" 
                 alt="<?php echo esc_attr($atts['alt']); ?>"
                 style="max-width: 100%; height: auto; <?php echo $style; ?>">
        </div>
        <?php
        return ob_get_clean();
    }
}

// Initialize the class when the file is included
$html_field = new html_Fields();