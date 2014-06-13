<div class="wrap">
    <h2>EventDove</h2>
    <form method="post" action="options.php"> 
        <?php @settings_fields('eventdove_plugin-group'); ?>
        <?php @do_settings_fields('eventdove_plugin-group'); ?>

        <?php do_settings_sections('eventdove_plugin'); ?>

        <?php @submit_button(); ?>
    </form>
</div>