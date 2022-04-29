<?php

namespace DPG\WordPress\EventApi\Api;

use DPG\WordPress\EventApi\Models\Event;

class Events
{
    /**
     * @return array
     */
    public static function getAll(bool $hideArchive = true): array
    {
        $response = EventApi::get('event');

        if (empty($response['items'])) {
            return [
                self::fillEvent(['title' => 'No events available']),
            ];
        }

        $events = [];
        foreach ($response['items'] as $item) {
            if ($hideArchive && ! empty($item['archived'])) {
                continue;
            }
            $events[] = self::fillEvent($item);
        }
        return $events;
    }

    /**
     * @param array $data
     *
     * @return Event
     */
    public static function fillEvent(array $data): Event
    {
        return new Event(
            $data['id'] ?? 0,
            $data['title'] ?? '',
            $data['status'] ?? '',
            $data['archived'] ?? false
        );
    }
}
