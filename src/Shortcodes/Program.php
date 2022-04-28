<?php
/**
 * Usage:
 * [dpg-ep-activities]
 */

namespace DPG\WordPress\EventApi\Shortcodes;

use DPG\WordPress\EventApi\Api\Activities;
use DPG\WordPress\EventApi\Api\Exhibitors;
use DPG\WordPress\EventApi\Models\Exhibitor;

class Program
{
    /**
     * Render template with twig
     *
     * @return void
     */
    public function show_html(): void
    {
        $context                    = \Timber::context();
        $context['activityList']    = Activities::getSorted();
        $context['active_activity'] = key($context['activityList']);
        $context['default_image']   = DPG_EVENTAPI_URL.'/assets/placeholder.png';

        \Timber::render('frontend/program.twig', $context);
    }

    /**
     * @param array  $stand
     * @param string $search_shop_query
     *
     * @return bool
     */
    private function need_to_add_exhibitor(array $stand, string $search_shop_query = ''): bool
    {
        if (empty($search_shop_query)) {
            return true;
        }

        if (strpos(strtolower($stand['name']), strtolower($search_shop_query)) !== false) {
            return true;
        }

        return false;
    }
}
