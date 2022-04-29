<?php

namespace DPG\WordPress\EventApi;

class Timber
{
    public function __construct()
    {
        add_filter(
            'timber/loader/paths',
            [$this, 'set_timber_path']
        );

        add_filter(
            'timber/twig',
            [$this, 'add_functions']
        );

        add_filter(
            'timber/twig',
            [$this, 'add_filters']
        );
    }

    /**
     * @param array $paths
     *
     * @return array
     */
    public function set_timber_path(array $paths): array
    {
        return array_merge(
            [
                trailingslashit(get_theme_file_path()).'dpg-wp-event-api-views/',
                DPG_EVENTAPI_PATH.'views/',
            ],
            $paths
        );
    }

    /**
     * @param \Twig\Environment $twig
     *
     * @return void
     */
    public function add_functions(\Twig\Environment $twig): void
    {
        $twig->addFunction(
            new \Twig\TwigFunction(
                'eventapi_settings_form',
                [$this, 'eventapi_settings_form']
            )
        );

        return $twig;
    }

    /**
     * @param \Twig\Environment $twig
     *
     * @return void
     */
    public function add_filters(\Twig\Environment $twig): void
    {
        $twig->addFilter(
            new \Twig\TwigFilter(
                'md5',
                [$this, 'md5']
            )
        );
        $twig->addFilter(
            new \Twig\TwigFilter(
                'localDate',
                [$this, 'local_date']
            )
        );

        return $twig;
    }

    /**
     * @param string $section
     * @param        $submitText
     *
     * @return void
     */
    public function eventapi_settings_form(string $section, $submitText = ''): void
    {
        settings_fields($section);
        do_settings_sections($section);
        submit_button($submitText);
    }

    /**
     * @param string $string
     *
     * @return void
     */
    public function md5(string $string): string
    {
        return md5($string);
    }

    /**
     * @param string $string
     * @param string $format
     *
     * @return string
     */
    public function local_date(string $string, string $format = 'l j F Y'): string
    {
        return date_i18n($format, strtotime($string));
    }
}
