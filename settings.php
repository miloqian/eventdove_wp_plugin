<?php
if(!class_exists('EventDove_Plugin_Settings'))
{
	class EventDove_Plugin_Settings
	{
		/**
		 * Construct the plugin object
		 */
		public function __construct()
		{
			// register actions
            add_action('admin_init', array(&$this, 'admin_init'));
        	add_action('admin_menu', array(&$this, 'add_menu'));
		} // END public function __construct
		
        /**
         * hook into WP's admin_init action hook
         */
        public function admin_init()
        {
        	// register your plugin's settings
        	register_setting('eventdove_plugin-group', 'access_token');
        	register_setting('eventdove_plugin-group', 'event_category_name');

        	// add your settings section
        	add_settings_section(
        	    'eventdove_plugin-section',
        	    'EventDove Settings',
        	    array(&$this, 'settings_section_eventdove_plugin'),
        	    'eventdove_plugin'
        	);
        	
        	// add your setting's fields
            add_settings_field(
                'eventdove_plugin-access_token',
                'Access Token',
                array(&$this, 'settings_field_input_text'), 
                'eventdove_plugin',
                'eventdove_plugin-section',
                array(
                    'field' => 'access_token'
                )
            );
            // add your setting's fields
            add_settings_field(
                'eventdove_plugin-event_category_name',
                'Category Name',
                array(&$this, 'settings_field_input_text'),
                'eventdove_plugin',
                'eventdove_plugin-section',
                array(
                    'field' => 'event_category_name'
                )
            );
//            add_settings_field(
//                'wp_plugin_template-setting_b',
//                'Setting B',
//                array(&$this, 'settings_field_input_text'),
//                'wp_plugin_template',
//                'wp_plugin_template-section',
//                array(
//                    'field' => 'setting_b'
//                )
//            );
            // Possibly do additional admin_init tasks
        } // END public static function activate
        
        public function settings_section_eventdove_plugin()
        {
            // Think of this as help text for the section.
            echo 'These settings do things for the WP Plugin Template.';
        }
        
        /**
         * This function provides text inputs for settings fields
         */
        public function settings_field_input_text($args)
        {
            // Get the field name from the $args array
            $field = $args['field'];
            // Get the value of this setting
            $value = get_option($field);
            // echo a proper input type="text"
            echo sprintf('<input type="text" name="%s" id="%s" value="%s" />', $field, $field, $value);
        } // END public function settings_field_input_text($args)
        
        /**
         * add a menu
         */		
        public function add_menu()
        {
            // Add a page to manage this plugin's settings
        	add_options_page(
        	    'EventDove Plugin Settings',
        	    'EventDove Plugin',
        	    'manage_options', 
        	    'eventdove_plugin',
        	    array(&$this, 'plugin_settings_page')
        	);
        } // END public function add_menu()
    
        /**
         * Menu Callback
         */		
        public function plugin_settings_page()
        {
//            include(sprintf("%s/templates/events.php", dirname(__FILE__)));
            if(!current_user_can('manage_options'))
        	{
        		wp_die(__('You do not have sufficient permissions to access this page.'));
        	}
	
        	// Render the settings template
        	include(sprintf("%s/templates/settings.php", dirname(__FILE__)));
        } // END public function plugin_settings_page()
    } // END class WP_Plugin_Template_Settings
} // END if(!class_exists('WP_Plugin_Template_Settings'))
