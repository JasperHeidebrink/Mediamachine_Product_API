<?php

namespace DPG\WordPress\EventApi\Api;

use DPG\WordPress\EventApi\Models\Exhibitor;

class Exhibitors
{
    /**
     * @return array
     */
    public static function getAll(): array
    {
        $current_edition_id = (int)get_option('eventapi_edition_id');
        $response           = EventApi::get('exhibitor', ['editionId' => $current_edition_id]);

        if (empty($response)) {
            return [
                self::fillItem(['title' => 'No exhibitors available']),
            ];
        }

        $events = [];
        foreach ($response['items'] as $item) {
            $events[] = self::fillItem($item);
        }

        return $events;
    }

    /**
     * @param array $data
     *
     * @return Exhibitor
     */
    public static function fillItem(array $data): Exhibitor
    {
        return new Exhibitor(
            $data['id'] ?? 0,
            $data['title'] ?? '',
            $data['url'] ?? '',
            $data['stand'] ?? '',
            $data['branches'] ?? '',
        );
    }
}
