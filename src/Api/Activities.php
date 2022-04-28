<?php

namespace DPG\WordPress\EventApi\Api;

use DPG\WordPress\EventApi\Models\Activity;

class Activities
{
    /**
     * @return array
     */
    public static function getAll(bool $hideArchive = true): array
    {
        $current_edition_id = (int)get_option('eventapi_edition_id');
        $response           = EventApi::get('activities', ['editionId' => $current_edition_id]);

        if (empty($response)) {
            return [
                self::fillItem(['title' => 'No activity available']),
            ];
        }

        $events = [];
        foreach ($response as $item) {
            if ($hideArchive && ! empty($item['archived'])) {
                continue;
            }
            $events[] = self::fillItem($item);
        }

        return $events;
    }

    /**
     * @return array
     */
    public static function getSorted(bool $hideArchive = true): array
    {
        $current_edition_id = (int)get_option('eventapi_edition_id');
        $activities         = EventApi::get('activities',
            ['editionId' => $current_edition_id],
            ['expand' => true]
        );

        if (empty($activities)) {
            return [
                self::fillItem(['title' => 'No activity available']),
            ];
        }

        $activities = self::splitActivitiesFromTimeslots($activities);

        return $activities;

//        echo '<pre style="background:#0f0; padding: 2rem; width:100%; z-index:9999">';
//        print_r($activities);
//        echo '</pre>';
//            die(__FILE__.':'.__LINE__);

        return self::activitiesSort($activities);
    }

    /**
     * @param array $data
     *
     * @return \DPG\WordPress\EventApi\Models\Activity
     */
    public static function fillItem(array $data): Activity
    {
        return new Activity(
            $data['id'] ?? 0,
            $data['title'] ?? '',
            $data['type'] ?? '',
            (int)$data['active'] ?? 1,
            $data['dateActive'] ?? '',
            $data['location'] ?? 1,
        );
    }

    private static function splitActivitiesFromTimeslots(array $activities)
    {
        $dayActivities = [];
        foreach ($activities as $activity) {
            if (
                strtotime($activity['dateActive']) > time() ||
                ! $activity['active'] ||
                ! empty($activity['archived'])
            ) {
                continue;
            }

            $timeboxes = $activity['timeboxes'];

            // sort timeboxes on starttime
            usort($timeboxes, function ($a, $b)
            {
                return ($a['timestampStart'] < $b['timestampStart']) ? -1 : 1;
            });

            $activity_type = strtolower(trim(wp_strip_all_tags($activity['type'])));

            foreach ($timeboxes as $timebox) {
                $readmore = wp_strip_all_tags($activity['readmore']);

                $dayActivities[$activity_type][$timebox['date']][] = [
                    'title'          => wp_strip_all_tags($activity['title']),
                    'type'           => $activity_type,
                    'date'           => $timebox['date'] ?? 0,
                    'start'          => $timebox['start'] ?? 0,
                    'end'            => $timebox['end'] ?? 0,
                    'description'    => wp_strip_all_tags($activity['description']),
                    'readmore'       => $readmore,
                    'image'          => wp_strip_all_tags($activity['media'][0]['url'] ?? ''),
                    'location'       => wp_strip_all_tags($timebox['location'] ?? ''),
                    'sublocation'    => wp_strip_all_tags($timebox['sublocation'] ?? ''),
                    'timestampStart' => $timebox['timestampStart'] ?? 0,
                    'timestampEnd'   => $timebox['timestampEnd'] ?? 0,
                    'activityId'     => $activity['id'],
                    'externalLink'   => (strpos($readmore, get_home_url()) === false),
                ];
            }
        }

        return $dayActivities;
    }

    private static function activitiesSort(array $activities): array
    {
        // sort on category
        ksort($activities);

        // sort within a category on days
        foreach ($activities as &$category_days_actitivies) {
            uksort($category_days_actitivies, function ($a, $b)
            {
                return strcmp($a, $b);
            });
        }

        // sort within a day on time slot then on title
        foreach ($activities as &$category_days_actitivies) {
            foreach ($category_days_actitivies as &$category_day_actitivies) {
                usort($category_day_actitivies, function ($a, $b)
                {
                    if ($a['timestampStart'] === $b['timestampStart']) {
                        return strcmp($a['title'], $b['title']);
                    }

                    return ($a['timestampStart'] > $b['timestampStart']) ? 1 : -1;
                });
            }
        }

        return $activities;
    }
}
