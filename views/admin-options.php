<?php


?>
<div class="wrap">
    <h2><?=DPG_EVENTAPI_NAME ?></h2>
    <form method="post" action="options.php">
        <?php
        // This prints out all hidden setting fields
        settings_fields( 'eventapi_options_group' );
        do_settings_sections( 'eventapi_settings' );
        submit_button();
        ?>
    </form>
</div>
