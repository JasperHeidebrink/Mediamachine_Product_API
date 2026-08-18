<div id="wpbody-content">
    <div class="wrap">
        <h2><?php _e('MediaMachine API Settings', MM_API_DOMAIN) ?></h2>

        <?php
        $cron_saved = \MMProductApi\Admin\Menu::get_admin_notice();
        if ('cron_saved' === $cron_saved) {
            echo '<div class="notice notice-success"><p>';
            _e('Cron schedule has been updated.', MM_API_DOMAIN);
            echo '</p></div>';
        }
        ?>

        <table class="form-table" role="presentation">
            <tbody>

            <?php
            $last_cron_run = get_option(\MMProductApi\Cron::LAST_RUN_OPTION);
            $cron_interval_minutes = \MMProductApi\Cron::get_interval_minutes();
            $last_cron_text = $last_cron_run ? wp_date('Y-m-d H:i:s', (int)$last_cron_run) : __('Never', MM_API_DOMAIN);
            ?>
            <tr>
                <th scope="row"><?= __('Cron schedule', MM_API_DOMAIN) ?></th>
                <td>
                    <p><?= __('Set how often wp-cron should run product stock and price updates.', MM_API_DOMAIN) ?></p>
                    <form method="post" action="<?= esc_url(admin_url('admin-post.php')) ?>"
                          style="margin-bottom: 10px;">
                        <input type="hidden" name="action" value="mm_api_cron_settings">
                        <?php wp_nonce_field('mm_api_cron_settings', 'mm_api_cron_settings_nonce'); ?>
                        <label for="mm_api_interval_minutes"><?= __('Interval (minutes):', MM_API_DOMAIN) ?></label>
                        <input
                                id="mm_api_interval_minutes"
                                name="mm_api_interval_minutes"
                                type="number"
                                min="1"
                                step="1"
                                value="<?= esc_attr((string)$cron_interval_minutes) ?>"
                                class="small-text"
                        >
                        <button type="submit" class="button button-primary">
                            <?php _e('Set cron', MM_API_DOMAIN) ?>
                        </button>
                    </form>
                    <p><strong><?= __('Last cron run:', MM_API_DOMAIN) ?></strong> <?= esc_html($last_cron_text) ?></p>
                </td>
            </tr>

            <tr>
                <th scope="row"><?= __('Reload data', MM_API_DOMAIN) ?></th>
                <td>
                    <p>
                        This will check all the products in on the website and update the price and stock, based on the <b>'MM product code'</b>.
                    </p>
                    <button type="button" class="button mm_api_reload">
                        <?php _e('Reload', MM_API_DOMAIN) ?>
                    </button>
                    <button type="button" class="button mm_api_stop hidden">
                        <?php _e('stop', MM_API_DOMAIN) ?>
                    </button>
                </td>
            </tr>

            <tr>
                <th scope="row"><?= __('Import new products', MM_API_DOMAIN) ?></th>
                <td>
                    <p>This will import new products from the API that don't exist on the website yet. New products will
                        be created with draft status.</p>
                    <button type="button" class="button mm_api_count_new_products">
                        <?php _e(
                                'Check for new products',
                                MM_API_DOMAIN
                        ) ?>
                    </button>

                    <div class="hidden mm-api-new-products-info">
                        <p class="mm-new-products-count"></p>
                        <button type="button" class="button mm_api_confirm_import">
                            <?php _e("Let's go", MM_API_DOMAIN) ?>
                        </button>
                        <button type="button" class="button mm_api_import_stop hidden">
                            <?php _e(
                                'Stop', MM_API_DOMAIN) ?>
                        </button>
                    </div>

                    <progress id="mm-import-progress" value="0" max="100" class="hidden"
                              style="width: 100%;"></progress>
                    <span id="mm-import-counter" class="hidden"
                          style="display: inline-block; margin-left: 10px;"></span>
                </td>
            </tr>

            <tr>
                <th scope="row"></th>
                <td>
                    <div class="mm-api-status"
                         style="border: 1px #333 solid; padding: 0.5rem 1rem; background: #eee;"></div>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
