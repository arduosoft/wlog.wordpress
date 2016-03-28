<?php

class WlogSetting
{
    /**
     * Holds the values to be used in the fields callbacks
     */
    private $options;

    /**
     * Start up
     */
    public function __construct()
    {
        add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
        add_action( 'admin_init', array( $this, 'page_init' ) );
    }

    /**
     * Add options page
     */
    public function add_plugin_page()
    {
        // This page will be under "Settings"
        add_options_page(
            'Settings Admin', 
            'Wlog Settings', 
            'manage_options', 
            'wlog-setting-admin', 
            array( $this, 'create_admin_page' )
        );
    }

    /**
     * Options page callback
     */
    public function create_admin_page()
    {
        // Set class property
        global $wlog_core;
        $this->options = $wlog_core->getOptions();
        ?>
        <div class="wrap">
            <h2>Wlog Settings</h2>           
            <form method="post" action="options.php">
            <?php
                // This prints out all hidden setting fields
                settings_fields( 'wlog_option_group' );   
                do_settings_sections( 'wlog-setting-admin' );
                submit_button(); 
            ?>
            </form>
        </div>
        <?php
    }

    /**
     * Register and add settings
     */
    public function page_init()
    {        
        register_setting(
            'wlog_option_group', // Option group
            WLOG_OPTIONS, // Option name
            array( $this, 'sanitize' ) // Sanitize
        );

        add_settings_section(
            'setting_section_id', // ID
            'Settings', // Title
            array( $this, 'print_section_info' ), // Callback
            'wlog-setting-admin' // Page
        );  

        add_settings_field(
            'wlog_key', // ID
            'Wlog Key', // Title 
            array( $this, 'wlog_key_callback' ), // Callback
            'wlog-setting-admin', // Page
            'setting_section_id' // Section           
        );      

        add_settings_field(
            'wlog_url', 
            'Wlog Url', 
            array( $this, 'wlog_url_callback' ), 
            'wlog-setting-admin', 
            'setting_section_id'
        );      
    }

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function sanitize( $input )
    {
        $new_input = array();
        if( isset( $input['wlog_key'] ) )
        {
            $new_input['wlog_key'] = sanitize_text_field( $input['wlog_key'] );
        }

        if( isset( $input['wlog_url'] ) )
        {
            $new_input['wlog_url'] = esc_url( $input['wlog_url'] );
        }

        return $new_input;
    }

    /** 
     * Print the Section text
     */
    public function print_section_info()
    {
        print 'Enter your settings below:';
    }

    /** 
     * Get the settings option array and print one of its values
     */
    public function wlog_key_callback()
    {
        printf(
            '<input size="60" type="text" id="wlog_key" name="'.WLOG_OPTIONS.'[wlog_key]" value="%s" />',
            isset( $this->options['wlog_key'] ) ? esc_attr( $this->options['wlog_key']) : ''
        );
    }

    /** 
     * Get the settings option array and print one of its values
     */
    public function wlog_url_callback()
    {
        printf(
            '<input size="60" type="text" id="wlog_url" name="'.WLOG_OPTIONS.'[wlog_url]" value="%s" />',
            isset( $this->options['wlog_url'] ) ? esc_attr( $this->options['wlog_url']) : ''
        );
    }
}

if( is_admin() )
{
	global $wlog_setting;
    $wlog_setting = new WlogSetting();
}


