<?php

namespace forgenet;

final class SweeperBot
{
	/**
	 * @return static
	 */
	public static function create()
	{
		return new static();
	}

	/**
	 * @return $this
	 */
	public function cleanHeadOutput()
	{
		add_action('wp_head', 'ob_start', 1, 0);
		add_action('wp_head', function () {
			$pattern = '/.*' . preg_quote(esc_url(get_feed_link('comments_' . get_default_feed())), '/') . '.*[\r\n]+/';
			echo preg_replace($pattern, '', ob_get_clean());
		}, 3, 0);
		remove_action('wp_head', 'rsd_link');
		remove_action('wp_head', 'wlwmanifest_link');
		remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10);
		remove_action('wp_head', 'wp_generator');
		remove_action('wp_head', 'wp_shortlink_wp_head', 10);
		remove_action('wp_head', 'print_emoji_detection_script', 7);
		remove_action('admin_print_scripts', 'print_emoji_detection_script');
		remove_action('wp_print_styles', 'print_emoji_styles');
		remove_action('admin_print_styles', 'print_emoji_styles');
		remove_action('wp_head', 'wp_oembed_add_discovery_links');
		remove_action('wp_head', 'wp_oembed_add_host_js');
		remove_action('wp_head', 'rest_output_link_wp_head', 10);
		remove_filter('the_content_feed', 'wp_staticize_emoji');
		remove_filter('comment_text_rss', 'wp_staticize_emoji');
		remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
		add_filter('use_default_gallery_style', '__return_false');
		add_filter('emoji_svg_url', '__return_false');

        if (!is_admin()) {
            wp_deregister_script('jquery');
            wp_register_script('jquery', '', '', '', true);
        }

		return $this;
	}

	/**
	 * @return $this
	 */
	public function removeRecentCommentsStyle()
	{
		global $wp_widget_factory;

		if (!isset($wp_widget_factory->widgets['WP_Widget_Recent_Comments'])) {
			return $this;
		}

		remove_action(
			'wp_head',
			[$wp_widget_factory->widgets['WP_Widget_Recent_Comments'],
				'recent_comments_style']
		);

		return $this;
	}

	/**
	 * @return $this
	 */
	public function removeWordPressVersionFromRssFeeds()
	{
		add_filter('the_generator', '__return_false');
		return $this;
	}
}