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
}
