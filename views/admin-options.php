<?php


?>
<div class="wrap">
    <h2><?=DPG_EVENTAPI_NAME ?></h2>
    <form method="post" action="options.php">
        <?php
        settings_fields( 'eventapi_settings_admin' );
        do_settings_sections( 'eventapi_settings_admin' );
        submit_button();
        ?>
    </form>
</div>
