<?php
use Timber\Timber;

require_once('vendor/autoload.php');

function setupTheme()
{
    Timber::$dirname = 'src/html';

    remove_filter('the_content', 'wpautop');
    register_nav_menus([
		'main' => 'Main menu',
	]);

	add_theme_support('post-thumbnails');
	add_theme_support('menu');
	add_theme_support('html5', ['comment-list', 'comment-form', 'search-form', 'gallery', 'caption']);

	add_filter('jpeg_quality', create_function( '', 'return 65;'));

    add_action('edit_form_after_title', 'addPostFields');
    add_action('save_post', 'savePostFields', 10, 3);
    add_action('admin_enqueue_scripts', 'loadAdminAreaCss');

    add_image_size('thumbnail', 120, 120, ['center', 'center']);
    add_image_size('thumbnail-retina', 240, 240, ['center', 'center']);
    add_image_size('small', 300, 99999);
    add_image_size('small-retina', 600, 99999);
    add_image_size('medium', 450, 99999);
    add_image_size('medium-retina', 900, 99999);
    add_image_size('large', 625, 99999);
    add_image_size('large-retina', 1250, 99999);
    add_image_size('xlarge', 1000, 99999);
    add_image_size('xlarge-retina', 2000, 99999);
}

function addPostFields()
{
    global $post;

    $context = [
        'postId' => $post->ID,
        'supTitle' => get_post_meta($post->ID, 'wps_before', true),
        'subTitle' => get_post_meta($post->ID,'wps_after',true),
        'introduction' => get_post_meta($post->ID,'wps_intro',true),
    ];

    echo Timber::fetch('admin/postFields.twig', $context);
}

function savePostFields($postId, $post, $update)
{
    if (array_key_exists('wps_before', $_POST)) {
        update_post_meta($postId, 'wps_before', sanitize_text_field($_POST['wps_before']));
    }

    if (array_key_exists('wps_after', $_POST)) {
        update_post_meta($postId, 'wps_after', sanitize_text_field($_POST['wps_after']));
    }

    if (array_key_exists('wps_intro', $_POST)) {
        update_post_meta($postId, 'wps_intro', sanitize_text_field($_POST['wps_intro']));
    }
}

function loadAdminAreaCss()
{
    wp_enqueue_style('admin-styles', get_template_directory_uri() . '/dist/css/admin.css');
}

function the_content_filter ($content) {
    $block = join("|", ["button"]);
    $rep = preg_replace("/(<p>)?\[($block)(\s[^\]]+)?\](<\/p>|<br \/>)?/","[$2$3]",$content);
    $rep = preg_replace("/(<p>)?\[\/($block)](<\/p>|<br \/>)?/","[/$2]",$rep);
    return $rep;
}

setupTheme();
