<?php
namespace forgenet;

use Timber\Site;
use Timber\Timber;

final class ShortCodeRenderer
{
    /**
     * @return static
     */
    public static function create()
    {
        return new static();
    }

    /**
     * @param string $shortCode
     * @param array $attributes
     * @return string
     */
    public function render($shortCode, $attributes)
    {
        $site = new Site();
        $attributes['site'] = $site;

        switch ($shortCode) {
            case 'section':
                return Timber::compile('layout/section.twig', $attributes);
            case 'pageTitle':
                return Timber::compile('components/pageTitle.twig', $attributes);
            case 'sectionTitle':
                return Timber::compile('components/sectionTitle.twig', $attributes);
            case 'countDown':
                return Timber::compile('components/countDown.twig', $attributes);
            case 'button':
                return Timber::compile('components/button.twig', $attributes);
            case 'tiles':
                return Timber::compile('components/tiles.twig', $attributes);
            case 'tile':
                return Timber::compile('components/tile.twig', $attributes);
            case 'cols':
                return Timber::compile('layout/cols.twig', $attributes);
            case 'col':
                return Timber::compile('layout/col.twig', $attributes);
            case 'textBlock':
                return Timber::compile('components/textBlock.twig', $attributes);
            case 'team':
                return Timber::compile('components/team.twig', $attributes);
            case 'member':
                return Timber::compile('components/member.twig', $attributes);
            case 'social':
                return Timber::compile('components/socialMediaLink.twig', $attributes);
            case 'timeLine':
                return Timber::compile('components/timeLine.twig', $attributes);
            case 'event':
                return Timber::compile('components/event.twig', $attributes);
            case 'thankYou':
                return Timber::compile('components/thankYou.twig', $attributes);
            default:
                return '';
        }
    }
}