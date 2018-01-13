<?php

namespace forgenet;

use Timber\Menu;
use Timber\Site;
use Timber\Timber;
use Twig_Environment;
use Twig_SimpleFunction;

final class SiteConfig extends Site
{
    public function __construct()
	{
		parent::__construct();
	}

	/**
	 * @return void
	 */
	public static function apply()
	{
		$config = new SiteConfig();
		$config->setDirectoryForTwigTemplates('src/html');
		$config->setupTwigEnvironment();
		$config->setupShortCodes();

        add_filter('script_loader_tag', [$config, 'setupScriptLoading'], 10, 2);
	}

	/**
	 * @param string $path
	 * @return void
	 */
	private function setDirectoryForTwigTemplates($path)
	{
		Timber::$dirname = $path;
	}

	private function setupTwigEnvironment()
	{
		add_filter('timber_context', [$this, 'addDefaultDataToTwigContext']);
		add_filter('get_twig', [$this, 'addMethodsToTwig']);
	}

    private function setupShortCodes()
    {
        $renderer = ShortCodeRenderer::create();

        add_shortcode('section', function ($attributes, $content = '') use ($renderer) {
            $content = do_shortcode($content);
            $attributes = $this->getNormalizedAttributes($attributes, $content);
            $attributes['isUserContent'] = true;
            return $renderer->render('section', $this->getNormalizedAttributes($attributes, $content));
        });
        add_shortcode('sectionTitle', function ($attributes, $content = '') use ($renderer) {
            return $renderer->render('sectionTitle', $this->getNormalizedAttributes($attributes, $content, 'text'));
        });
        add_shortcode('countDown', function ($attributes) use ($renderer) {
            return $renderer->render('countDown', $this->getNormalizedAttributes($attributes));
        });
        add_shortcode('button', function ($attributes, $content = '') use ($renderer) {
            return $renderer->render('button', $this->getNormalizedAttributes($attributes, $content, 'title'));
        });
        add_shortcode('tile', function ($attributes, $content = '') use ($renderer) {
            return $renderer->render('tile', $this->getNormalizedAttributes($attributes, $content));
        });
        add_shortcode('tiles', function ($attributes, $content = '') use ($renderer) {
            $content = do_shortcode($content);
            return $renderer->render('tiles', $this->getNormalizedAttributes($attributes, $content));
        });
        add_shortcode('cols', function ($attributes, $content = '') use ($renderer) {
            $content = do_shortcode($content);
            return $renderer->render('cols', $this->getNormalizedAttributes($attributes, $content));
        });
        add_shortcode('col', function ($attributes, $content = '') use ($renderer) {
            $content = do_shortcode($content);
            return $renderer->render('col', $this->getNormalizedAttributes($attributes, $content));
        });
        add_shortcode('textBlock', function ($attributes, $content = '') use ($renderer) {
            $content = do_shortcode($content);
            return $renderer->render('textBlock', $this->getNormalizedAttributes($attributes, $content));
        });
        add_shortcode('team', function ($attributes, $content = '') use ($renderer) {
            $content = do_shortcode($content);
            return $renderer->render('team', $this->getNormalizedAttributes($attributes, $content));
        });
        add_shortcode('member', function ($attributes, $content = '') use ($renderer) {
            $content = do_shortcode($content);
            return $renderer->render('member', $this->getNormalizedAttributes($attributes, $content));
        });
        add_shortcode('social', function ($attributes, $content = '') use ($renderer) {
            return $renderer->render('social', $this->getNormalizedAttributes($attributes, $content, 'title'));
        });
        add_shortcode('timeLine', function ($attributes, $content = '') use ($renderer) {
            $content = do_shortcode($content);
            return $renderer->render('timeLine', $this->getNormalizedAttributes($attributes, $content));
        });
        add_shortcode('event', function ($attributes, $content = '') use ($renderer) {
            return $renderer->render('event', $this->getNormalizedAttributes($attributes, $content));
        });
        add_shortcode('thankYou', function ($attributes, $content = '') use ($renderer) {
            return $renderer->render('thankYou', $this->getNormalizedAttributes($attributes, $content));
        });
    }

    /**
     * @param mixed $attributes
     * @param mixed $content
     * @param string $contentKey
     * @return array
     */
    public function getNormalizedAttributes ($attributes, $content = '', $contentKey = 'content')
    {
        $normalizedAttributes = [];

        if (is_array($attributes)) {
            $normalizedAttributes = $normalizedAttributes + $attributes;
        }

        if ($content !== '') {
            $normalizedAttributes[$contentKey] = $content;
        }

        return $normalizedAttributes;
    }

	/**
	 * @param array $context
	 * @return mixed
	 */
	public function addDefaultDataToTwigContext(array $context)
	{
		$context['menu'] = new Menu();
		$context['site'] = $this;
		return $context;
	}

	/**
	 * @param Twig_Environment $twigEnvironment
	 * @return mixed
	 */
	public function addMethodsToTwig(Twig_Environment $twigEnvironment)
	{
		$twigEnvironment->addFunction(
			new Twig_SimpleFunction('loadStyle',
			function ($handle) {
				$templateDirectory = get_template_directory();
				$templateDirectoryUri = get_template_directory_uri();
				$distDirectory = 'dist/css';

				clearstatcache();
				$fileVersion = filemtime("{$templateDirectory}/{$distDirectory}/{$handle}.css");

				wp_enqueue_style(
					$handle,
					"{$templateDirectoryUri}/{$distDirectory}/{$handle}.css",
					[],
					$fileVersion
				);
			})
		);

        $twigEnvironment->addFunction(
            new Twig_SimpleFunction('loadScript',
                function ($handle) {
                    $templateDirectory = get_template_directory();
                    $templateDirectoryUri = get_template_directory_uri();
                    $distDirectory = 'dist/js';

                    clearstatcache();
                    $fileVersion = filemtime("{$templateDirectory}/{$distDirectory}/{$handle}.min.js");

                    wp_enqueue_script(
                        $handle,
                        "{$templateDirectoryUri}/{$distDirectory}/{$handle}.min.js",
                        [],
                        $fileVersion,
                        true
                    );
                })
        );

		return $twigEnvironment;
	}

    /**
     * @param string $tag
     * @param string $handle
     * @return mixed
     */
    public function setupScriptLoading($tag, $handle)
    {
        $tag = str_replace(' src',' async src', $tag);
        $tag = str_replace(" type='text/javascript'", '', $tag);
        return $tag;
    }
}