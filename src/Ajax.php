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
//        add_action('wp_ajax_dpgevent_data', [$this, 'get_eventapi_data']);
        add_action('wp_ajax_dpgeventhtml_activities', [$this, 'get_dpgeventhtml_activities']);
        add_action('wp_ajax_dpgeventhtml_exhibitors', [$this, 'get_dpgeventhtml_exhibitors']);
//        add_action('wp_ajax_nopriv_dpgeventhtml', [$this, 'get_eventapi_html']);
//        do_action( 'wp_ajax_nopriv_', [$this, ''] );
    }


    function get_eventapi_data()
    {
        $activityList = Activities::getAll();
        if (empty($activityList)) {
            wp_send_json(['data' => 'empty']);
        }

        wp_send_json($activityList);
    }

    /**
     * @return void
     */
    public function get_dpgeventhtml_activities()
    {
        $activityList = Activities::getAll();
        if (empty($activityList) || empty($activityList[0]->getId())) {
            wp_send_json_error(__('No Activities found', DPG_EVENTAPI_SLUG));
        }

        ob_start();
        include_once DPG_EVENTAPI_PATH.'views/admin/activities.php';
        $html = ob_get_contents();
        ob_end_clean();

        wp_send_json_success($html);
    }

    /**
     * @return void
     */
    public function get_dpgeventhtml_exhibitors()
    {
        $exhibitorList = Exhibitors::getAll();
        if (empty($exhibitorList) || empty($exhibitorList[0]->getId())) {
            wp_send_json_error(__('No Exhibitors found', DPG_EVENTAPI_SLUG));
        }

        ob_start();
        include_once DPG_EVENTAPI_PATH.'views/admin/exhibitors.php';
        $html = ob_get_contents();
        ob_end_clean();

        wp_send_json_success($html);
    }
}
