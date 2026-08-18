<?php
/**
 * Cron handling for periodic product updates.
 *
 * @package MM_Product_API
 */

namespace MMProductApi;

class Cron
{
    public const EVENT_HOOK = 'mm_product_api_update_prices';
    public const OPTION_INTERVAL_MINUTES = 'mm_product_api_cron_interval_minutes';
    public const LAST_RUN_OPTION = 'mm_product_api_last_cron_run';
    public const DEFAULT_INTERVAL_MINUTES = 5;
    public const MIN_INTERVAL_MINUTES = 1;

    public function __construct()
    {
        add_filter('cron_schedules', [$this, 'add_schedule']);
        add_action('init', [$this, 'ensure_schedule']);
        add_action(self::EVENT_HOOK, [$this, 'run_scheduled_update']);
    }

    /**
     * Add custom interval.
     *
     * @param array $schedules
     *
     * @return array
     */
    public function add_schedule(array $schedules): array
    {
        return self::append_configured_schedule($schedules);
    }

    /**
     * Ensure the event is scheduled.
     *
     * @return void
     */
    public function ensure_schedule(): void
    {
        $event = wp_get_scheduled_event(self::EVENT_HOOK);
        if (!$event) {
            self::reset_schedule();
            return;
        }

        $recurrence = self::get_recurrence_key();
        if ($event->schedule === $recurrence) {
            return;
        }

        self::reset_schedule();
    }

    /**
     * Execute scheduled updates for all product pages.
     *
     * @return void
     */
    public function run_scheduled_update(): void
    {
        $updater = new ProductUpdater();
        $page    = 1;

        while (true) {
            $result = $updater->update_prices_from_api($page);

            if (!is_array($result)) {
                $page++;
                continue;
            }

            break;
        }

        update_option(self::LAST_RUN_OPTION, current_time('timestamp'));
    }

    /**
     * Reset cron event to the currently configured interval.
     *
     * @return void
     */
    public static function reset_schedule(): void
    {
        add_filter('cron_schedules', [self::class, 'append_configured_schedule']);
        wp_clear_scheduled_hook(self::EVENT_HOOK);
        wp_schedule_event(time(), self::get_recurrence_key(), self::EVENT_HOOK);
        remove_filter('cron_schedules', [self::class, 'append_configured_schedule']);
    }

    /**
     * Activation callback.
     *
     * @return void
     */
    public static function activate(): void
    {
        if (get_option(self::OPTION_INTERVAL_MINUTES, null) === null) {
            add_option(self::OPTION_INTERVAL_MINUTES, self::DEFAULT_INTERVAL_MINUTES);
        }

        self::reset_schedule();
    }

    /**
     * Deactivation callback.
     *
     * @return void
     */
    public static function deactivate(): void
    {
        wp_clear_scheduled_hook(self::EVENT_HOOK);
    }

    /**
     * Add configured schedule entry to the schedule array.
     *
     * @param array $schedules
     *
     * @return array
     */
    public static function append_configured_schedule(array $schedules): array
    {
        $minutes = self::get_interval_minutes();
        $schedules[self::get_recurrence_key($minutes)] = [
            'interval' => $minutes * MINUTE_IN_SECONDS,
            'display'  => sprintf(__('Every %d minutes', MM_API_DOMAIN), $minutes),
        ];

        return $schedules;
    }

    /**
     * Store and normalize interval in minutes.
     *
     * @param int $minutes
     *
     * @return void
     */
    public static function set_interval_minutes(int $minutes): void
    {
        update_option(self::OPTION_INTERVAL_MINUTES, self::normalize_interval_minutes($minutes));
    }

    /**
     * Get configured interval in minutes.
     *
     * @return int
     */
    public static function get_interval_minutes(): int
    {
        $minutes = (int) get_option(self::OPTION_INTERVAL_MINUTES, self::DEFAULT_INTERVAL_MINUTES);
        return self::normalize_interval_minutes($minutes);
    }

    /**
     * Build recurrence key.
     *
     * @param int|null $minutes
     *
     * @return string
     */
    public static function get_recurrence_key(?int $minutes = null): string
    {
        if ($minutes === null) {
            $minutes = self::get_interval_minutes();
        }

        return 'mm_api_every_' . $minutes . '_minutes';
    }

    /**
     * Ensure valid interval.
     *
     * @param int $minutes
     *
     * @return int
     */
    private static function normalize_interval_minutes(int $minutes): int
    {
        if ($minutes < self::MIN_INTERVAL_MINUTES) {
            return self::DEFAULT_INTERVAL_MINUTES;
        }

        return $minutes;
    }
}
