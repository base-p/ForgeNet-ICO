<?php

require_once('vendor/autoload.php');

use Timber\Helper;
use Timber\Post;
use Timber\Timber;
use forgenet\SiteConfig;
use forgenet\SweeperBot;

try {
	SiteConfig::apply();

	$sweeperBot = SweeperBot::create()
		->cleanHeadOutput()
		->removeRecentCommentsStyle()
		->removeWordPressVersionFromRssFeeds();

	$context = Timber::get_context();
    $context['post'] = getPost();

    if (is_front_page()) {
        return Timber::render('home.twig', $context);
    }

    if (is_page()) {
        return Timber::render('page.twig', $context);
    }

	return Timber::render('404.twig', $context);
} catch (Twig_Error_Loader $unableToFindTwigFile) {
	echo 'Oops, something went wrong';
	throw $unableToFindTwigFile;
}

function getPost()
{
    $post = new Post();

    if (isset($post->wps_after)) {
        $post->subTitle = $post->wps_after;
        unset($post->wps_after);
    }

    if (isset($post->wps_intro)) {
        $post->introduction = $post->wps_intro;
        unset($post->wps_intro);
    }

    if (isset($post->wps_before)) {
        $post->supTitle = $post->wps_before;
        unset($post->wps_before);
    }

    setMetaDescription($post);
    return $post;
}

function setMetaDescription(Post $post)
{
    $description = $post->get_field('_yoast_wpseo_metadesc');

    if (empty($description)) {
        $description = wptexturize($post->get_field('meta_description'));
    }

    if (empty($description)) {
        $description = str_replace('', "'", $post->get_preview(40, true, false, true));
    }

    if (empty($description)) {
        $description = get_bloginfo('description', 'raw');
    }

    if (strlen($description) > 300) {
        $description = substr($description, 0, 200);
        $description = Helper::trim_words($description, str_word_count($description) - 1);
    }

    $post->metaDescription = $description;
}