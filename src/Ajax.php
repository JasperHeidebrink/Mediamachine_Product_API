<?php
/**
 * DPG Media Magazines -  WordPress EventApi
 *
 * @package   DPG_WP_EventApi
 */

namespace DPG\WordPress\EventApi;

use DPG\WordPress\EventApi\Api\Activities;
use DPG\WordPress\EventApi\Api\Exhibitors;

class Ajax
{
    public function __construct()
    {
        add_action('wp_ajax_dpgeventhtml_activities', [$this, 'get_dpgeventhtml_activities']);
        add_action('wp_ajax_dpgeventhtml_exhibitors', [$this, 'get_dpgeventhtml_exhibitors']);
    }

    /**
     * @return void
     */
    public function get_dpgeventhtml_activities(): void
    {
        $activityList = Activities::getAll();
        if (empty($activityList) || empty($activityList[0]->getId())) {
            wp_send_json_error(__('No Activities found', DPG_EVENTAPI_SLUG));
        }

        $context                 = \Timber::context();
        $context['activityList'] = $activityList;

        ob_start();
        \Timber::render('admin/activities.twig', $context);
        $html = ob_get_clean();

        wp_send_json_success($html);
    }

    /**
     * @return void
     */
    public function get_dpgeventhtml_exhibitors(): void
    {
        $exhibitorList = Exhibitors::getAll();
        if (empty($exhibitorList) || empty($exhibitorList[0]->getId())) {
            wp_send_json_error(__('No Exhibitors found', DPG_EVENTAPI_SLUG));
        }

        $context                  = \Timber::context();
        $context['exhibitorList'] = $exhibitorList;

        ob_start();
        \Timber::render('admin/exhibitors.twig', $context);
        $html = ob_get_clean();

        wp_send_json_success($html);
    }
}
