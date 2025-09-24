<?php
/**
 * Plugin Name: Ultra Blank Slate Editor
 * Description: Code snippets for creating dynamic editor
 * !
 * Version: 1.0
 * Author: Bada Bing
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('MCP_PLUGIN_URL', plugin_dir_url(__FILE__));
define('MCP_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('MCP_PLUGIN_VERSION', '1.0.0');

// Kill Gutenberg completely - for ALL post types
add_filter('use_block_editor_for_post_type', '__return_false', 999);
add_filter('use_block_editor_for_post', '__return_false', 999);

function add_my_custom_editor()
{

    $post_type = ['page', 'post'];
    foreach ($post_type as $type):
        remove_post_type_support($type, 'editor');

        // Main editor in normal position
        add_meta_box(
            'my-custom-editor-page',      // ID
            'My Beautiful Editor',        // Title  
            'display_my_custom_editor',   // Callback function
            $type,                       // Post type
            'normal',                     // Position
            'high'                        // Priority
        );

        // Side content in side position - this is what you wanted, right?
        add_meta_box(
            'my-side-content-box',        // ID
            'Templates',                  // Title
            'display_side_content_box',   // Callback function
            $type,                        // Post type
            'side',                       // Position - this is the magic word!
            'high'                        // Priority
        );

    endforeach;
}
add_action('add_meta_boxes', 'add_my_custom_editor');

add_action('add_meta_boxes', 'my_remove_default_meta_boxes', 100);

function my_remove_default_meta_boxes()
{

    $post_type = ['page', 'post'];
    foreach ($post_type as $type):

        // Remove Featured Image box
        remove_meta_box('postimagediv', $type, 'side');

        // Remove Publish box
        // remove_meta_box('submitdiv', $type, 'side');

        // Remove Page Attributes
        remove_meta_box('pageparentdiv', $type, 'side');

        // Remove Page Revisions
        remove_meta_box('revisionsdiv', $type, 'normal');

        // Remove Comments
        remove_meta_box('commentsdiv', $type, 'normal');

    endforeach;
}


// Enqueue styles and scripts for admin pages - do this BEFORE the page loads!
function enqueue_dynamic_editor_assets($hook)
{
    // Only load on post edit pages
    if ($hook !== 'post.php' && $hook !== 'post-new.php') {
        return;
    }

    // Only load for posts and pages that we're actually editing
    global $post;
    if (!$post || !in_array($post->post_type, ['post', 'page'])) {
        return;
    }

    // Enqueue CSS - using the plugin URL constant like a smart guy
    wp_enqueue_style(
        'dynamic-editor-css',
        MCP_PLUGIN_URL . 'css/dynamic-editor.css',
        array(),
        MCP_PLUGIN_VERSION
    );

    // Enqueue JavaScript - put it in a js folder where it belongs!
    wp_enqueue_script(
        'dynamic-editor-js',
        MCP_PLUGIN_URL . 'js/dynamic-editor.js',
        array('jquery'),
        MCP_PLUGIN_VERSION,
        true // Load in footer
    );
}
add_action('admin_enqueue_scripts', 'enqueue_dynamic_editor_assets');

function display_my_custom_editor($post)
{
    // Add a nonce field for security
    wp_nonce_field('save_custom_editor', 'custom_editor_nonce');

    // Get the current content
    $content = $post->post_content;

    // Your custom editor HTML goes here
    echo '<div id="my-editor-container" style="margin-top: 10px;">';
    echo '<textarea name="my_custom_content" id="my-custom-content" rows="20" cols="100" style="width: 100%; font-family: monospace;">' . esc_textarea($content) . '</textarea>';
    echo '</div>';
}

// This is your new function for the side content - beautiful!
function display_side_content_box($post)
{
    // Check if the side-content.php file exists first - we ain't amateurs here
    $side_content_file = MCP_PLUGIN_PATH . 'side-content.php';
    
    if (file_exists($side_content_file)) {
        // Include the file and capture its output
        ob_start();
        include $side_content_file;
        $side_content = ob_get_clean();
        
        // Display it in the meta box
        echo '<div id="side-content-container">';
        echo $side_content;
        echo '</div>';
    } else {
        // Fallback if file doesn't exist - we're professionals here
        echo '<div class="notice notice-warning inline">';
        echo '<p>Side content template not found. Expected location: ' . $side_content_file . '</p>';
        echo '</div>';
    }
}

// Save the content from your custom editor
function save_my_custom_editor($post_id)
{
    // Check if nonce is valid
    if (!isset($_POST['custom_editor_nonce']) || !wp_verify_nonce($_POST['custom_editor_nonce'], 'save_custom_editor')) {
        return;
    }

    // Check if user has permission to edit this post
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    // Don't save during autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (isset($_POST['my_custom_content'])) {
        $content = wp_kses_post($_POST['my_custom_content']);

        // Remove the hook temporarily to avoid infinite loop
        remove_action('save_post', 'save_my_custom_editor');

        wp_update_post(array(
            'ID' => $post_id,
            'post_content' => $content
        ));

        // Add the hook back
        add_action('save_post', 'save_my_custom_editor');
    }
}
add_action('save_post', 'save_my_custom_editor');

// Optional: Add some admin notices to let people know what's happening
// function custom_editor_admin_notice()
// {
//     if (get_current_screen()->base === 'post') {

//         ob_start();
?>
        <!-- <div class="notice notice-info">
            <p><strong>Dynamic Editor Active:</strong>
                Using custom editor instead of WordPress default. Now with side content too - very nice! Capisce?
            </p>
        </div> -->
<?php
//         $content = ob_get_clean();
//         echo $content;
//     }
// }
// add_action('admin_notices', 'custom_editor_admin_notice');
?>