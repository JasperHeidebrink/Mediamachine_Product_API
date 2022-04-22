<?php

namespace DPG\WordPress\EventApi\Api;

use DPG\WordPress\EventApi\Models\Edition;

class Editions
{
    /**
     * @return array
     */
    public static function getAllEditions(int $editionId, bool $hideArchive = true): array
    {
        $response = EventApi::get('eventEdition', ['eventId'=>$editionId]);

        if (empty($response['items'])) {
            return [
                self::fillEdition(['title' => 'No editions available']),
            ];
        }


        $events = [];
        foreach ($response['items'] as $item) {
            if ($hideArchive && ! empty($item['archived'])) {
                continue;
            }
            $events[] = self::fillEdition($item);
        }

        return $events;
    }

    /**
     * @param array $data
     *
     * @return \DPG\WordPress\EventApi\Models\Edition
     */
    public static function fillEdition(array $data): Edition
    {
        return new Edition(
            $data['id'] ?? 0,
            $data['title'] ?? '',
            $data['status'] ?? '',
            $data['start'] ?? '',
            $data['end'] ?? '',
            $data['content'] ?? '',
            $data['archived'] ?? false
        );
    }
}
