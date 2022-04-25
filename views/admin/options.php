<?php


?>
<div class="wrap">

    <h2><?= DPG_EVENTAPI_NAME ?></h2>

    <div class="card" style="min-width:50%;">
        <form method="post" action="options.php">
            <?php
            settings_fields('eventapi_settings_admin');
            do_settings_sections('eventapi_settings_admin');
            submit_button();
            ?>
        </form>
    </div>

    <div class="card" style="min-width:50%;">
        <form method="post" action="options.php">
            <?php
            settings_fields('eventapi_clear_cache_admin');
            do_settings_sections('eventapi_clear_cache_admin');
            submit_button('Reload Event Data');
            ?>
        </form>
    </div>

    <div class="wrap">
        <div class="card" style="min-width:50%;">
            <h2>Event data:</h2>
            <button id="activities" class="ajaxload_activities button button-primary">
                <?= __('Show Activities', DPG_EVENTAPI_SLUG) ?>
            </button>
            <button id="exhibitors" class="ajaxload_exhibitors button button-primary">
                <?= __('Show Exhibitors', DPG_EVENTAPI_SLUG) ?>
            </button>
            <hr>
            <div class="ajaxdata"></div>
        </div>
    </div>

</div>
