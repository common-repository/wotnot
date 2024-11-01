<?php

/**
 * Plugin Name: Chatbot & Live Chat for WP - WotNot
 * Version: 1.0
 * Plugin URI: https://wotnot.io
 * Description: Add a Free Chatbot to your WordPress to automate lead generation and scale your customer support - with zero code.
 * Author: WotNot
 * Author URI: https://profiles.wordpress.org/hardikmakadia/
 * License: GPLv2 or later
 */

// exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

define('WOTNOT_PLUGIN_DIR', str_replace('\\', '/', dirname(__FILE__)));

if (!class_exists('WotNotScriptLoader')) {

    class WotNotScriptLoader
    {


        function __construct()
        {

            add_action('admin_init', array(
                &$this,
                'admin_init'
            ));

            add_action('admin_menu', array(
                &$this,
                'admin_menu'
            ));

            add_action('wp_head', array(
                &$this,
                'wp_head'
            ));

            $plugin = plugin_basename(__FILE__);
            add_filter("plugin_action_links_$plugin", array(
                &$this,
                'wotnot_settings_link'
            ));


            // Activation page auto redirect
            add_action('activated_plugin', array(
                &$this,
                'wotnot_activation_redirect'
            ));

        }


        function wotnot_activation_redirect($plugin)
        {
            if ($plugin == plugin_basename(__FILE__)) {
                exit(wp_redirect(admin_url('admin.php?page=wotnot')));
            }
        }

        function wotnot_settings_link($links)
        {
            $settings_link = '<a href="admin.php?page=wotnot">' . __('Settings') . '</a>';
            $support_link = '<a href="https://wotnot.atlassian.net/servicedesk/customer/portals" target="_blank">' . __('Support') . '</a>';

            array_push($links, $settings_link);
            array_push($links, $support_link);

            return $links;
        }

        function admin_init()
        {
            // register style
            wp_register_style('wotnot', plugins_url('css/main.css', __FILE__));
            wp_enqueue_style('wotnot');

            // register settings for sitewide script
            register_setting('wotnot-settings-group', 'wotnot-plugin-settings');

            add_settings_field('script', 'Script', 'trim', 'wotnot');
            add_settings_field('showOn', 'Show On', 'trim', 'wotnot');
            add_settings_field('installedOn', 'Show On', 'trim', 'wotnot');

            // default value for settings
            $initialSettings = get_option('wotnot-plugin-settings');
            if ($initialSettings === false) {
                $initialSettings['showOn'] = 'all';
                $initialSettings['installedOn'] = date("Y/m/d");
                update_option('wotnot-plugin-settings', $initialSettings);
            }
            if ($initialSettings === true && !$initialSettings['showOn']) {
                $initialSettings['showOn'] = 'all';
                update_option('wotnot-plugin-settings', $initialSettings);
            }
            if ($initialSettings === true && !$initialSettings['installedOn']) {
                $initialSettings['installedOn'] = date("Y/m/d");
                update_option('wotnot-plugin-settings', $initialSettings);
            }
        }

        // adds menu item to wordpress admin dashboard
        function admin_menu()
        {
            add_menu_page(__('WotNot', 'wotnot-settings'), __('WotNot', 'wotnot-settings'), 'manage_options', 'wotnot', array(
                &$this,
                'wotnot_options_panel'
            ), 'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz4NCjwhLS0gR2VuZXJhdG9yOiBBZG9iZSBJbGx1c3RyYXRvciAyMy4wLjMsIFNWRyBFeHBvcnQgUGx1Zy1JbiAuIFNWRyBWZXJzaW9uOiA2LjAwIEJ1aWxkIDApICAtLT4NCjxzdmcgdmVyc2lvbj0iMS4xIiBpZD0iTGF5ZXJfMSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgeD0iMHB4IiB5PSIwcHgiDQoJIHZpZXdCb3g9IjAgMCA3NS40IDU3LjQiIHN0eWxlPSJlbmFibGUtYmFja2dyb3VuZDpuZXcgMCAwIDc1LjQgNTcuNDsiIHhtbDpzcGFjZT0icHJlc2VydmUiPg0KPHN0eWxlIHR5cGU9InRleHQvY3NzIj4NCgkuc3Qwe29wYWNpdHk6MC45O2VuYWJsZS1iYWNrZ3JvdW5kOm5ldyAgICA7fQ0KCS5zdDF7ZmlsbDojMjQ5REEzO30NCgkuc3Qye2ZpbGw6IzAwMkM4Njt9DQoJLnN0M3tvcGFjaXR5OjAuODtlbmFibGUtYmFja2dyb3VuZDpuZXcgICAgO30NCgkuc3Q0e2ZpbGw6IzBBNTc5NTt9DQoJLnN0NXtlbmFibGUtYmFja2dyb3VuZDpuZXcgICAgO30NCgkuc3Q2e2ZpbGw6IzJBMkIzMDt9DQo8L3N0eWxlPg0KPGcgaWQ9Ikdyb3VwXzEwMSIgdHJhbnNmb3JtPSJ0cmFuc2xhdGUoLTYzMC44NTQgLTEzOC45MjcpIj4NCgk8ZyBpZD0iR3JvdXBfNDMiIHRyYW5zZm9ybT0idHJhbnNsYXRlKDE4MzkuOTg0IC0xMzMuNDA5KSI+DQoJCTxnIGlkPSJHcm91cF80MiIgdHJhbnNmb3JtPSJ0cmFuc2xhdGUoLTEyMDkuMTMgMjcyLjMzNikiPg0KCQkJPGcgaWQ9IlBhdGhfMjUiIGNsYXNzPSJzdDAiPg0KCQkJCTxwYXRoIGNsYXNzPSJzdDEiIGQ9Ik0xNS4yLDkuNWMxLjIsMCwyLjMsMC40LDMuMiwxbDguOSw2LjJsLTguOSw2LjJjLTAuOSwwLjctMi4xLDEtMy4yLDFjLTMuMSwwLTUuNy0yLjUtNS43LTUuN2MwLDAsMCwwLDAsMA0KCQkJCQl2LTNDOS41LDEyLjEsMTIuMSw5LjUsMTUuMiw5LjVDMTUuMiw5LjUsMTUuMiw5LjUsMTUuMiw5LjUgTTE1LjIsMEwxNS4yLDBjLTIsMC0zLjksMC40LTUuNywxLjFDNy43LDEuOCw2LjEsMi45LDQuNyw0LjINCgkJCQkJQzQsNC45LDMuNCw1LjYsMi44LDYuNEMyLjIsNy4zLDEuNyw4LjIsMS4zLDkuMWMtMC40LDEtMC43LDItMSwzYy0wLjIsMS0wLjMsMi4xLTAuMywzLjJ2M2MwLDEuMSwwLjEsMi4xLDAuMywzLjINCgkJCQkJYzAuMiwxLDAuNSwyLDEsM2MwLjQsMC45LDAuOSwxLjgsMS41LDIuNmMwLjYsMC44LDEuMiwxLjUsMS45LDIuMmMxLjQsMS4zLDMsMi40LDQuOCwzLjFjMS44LDAuNywzLjcsMS4xLDUuNywxLjENCgkJCQkJYzMuMSwwLDYuMS0xLDguNi0yLjdsOC45LTYuMmwxMS4zLTcuOEwzMi44LDguOWwtOC45LTYuMkMyMS4zLDEsMTguMywwLDE1LjIsMEwxNS4yLDB6Ii8+DQoJCQk8L2c+DQoJCQk8ZyBpZD0iUGF0aF8yNiIgY2xhc3M9InN0MCI+DQoJCQkJPHBhdGggY2xhc3M9InN0MiIgZD0iTTU2LjEsNDcuOWMtMS4yLDAtMi4zLTAuNC0zLjItMUw0NCw0MC43bDguOS02LjJjMC45LTAuNywyLjEtMSwzLjItMWMzLjEsMCw1LjcsMi41LDUuNyw1LjdjMCwwLDAsMCwwLDB2Mw0KCQkJCQlDNjEuOCw0NS4zLDU5LjMsNDcuOSw1Ni4xLDQ3LjlDNTYuMSw0Ny45LDU2LjEsNDcuOSw1Ni4xLDQ3LjkgTTU2LjEsNTcuNEw1Ni4xLDU3LjRjMiwwLDMuOS0wLjQsNS43LTEuMQ0KCQkJCQljMS44LTAuNywzLjQtMS44LDQuOC0zLjFjMC43LTAuNywxLjQtMS40LDEuOS0yLjJjMC42LTAuOCwxLjEtMS43LDEuNS0yLjZjMC40LTEsMC43LTIsMS0zYzAuMi0xLDAuMy0yLjEsMC4zLTMuMnYtMw0KCQkJCQljMC0xLjEtMC4xLTIuMS0wLjMtMy4yYy0wLjItMS0wLjUtMi0xLTNjLTAuNC0wLjktMC45LTEuOC0xLjUtMi42Yy0wLjYtMC44LTEuMi0xLjUtMS45LTIuMmMtMS40LTEuMy0zLTIuNC00LjgtMy4xDQoJCQkJCWMtMS44LTAuNy0zLjctMS4xLTUuNy0xLjFjLTMuMSwwLTYuMSwxLTguNiwyLjdsLTguOSw2LjJsLTExLjMsNy44bDExLjMsNy44bDguOSw2LjJDNTAsNTYuNCw1Myw1Ny40LDU2LjEsNTcuNEw1Ni4xLDU3LjR6Ii8+DQoJCQk8L2c+DQoJCQk8ZyBpZD0iUGF0aF8yNyIgY2xhc3M9InN0MyI+DQoJCQkJPHBhdGggY2xhc3M9InN0NCIgZD0iTTE1LjIsMzMuNWMxLjIsMCwyLjMsMC40LDMuMiwxbDguOSw2LjJsLTguOSw2LjJjLTAuOSwwLjctMi4xLDEtMy4yLDFjLTMuMSwwLTUuNy0yLjUtNS43LTUuN2MwLDAsMCwwLDAsMA0KCQkJCQl2LTNDOS41LDM2LDEyLjEsMzMuNSwxNS4yLDMzLjVDMTUuMiwzMy41LDE1LjIsMzMuNSwxNS4yLDMzLjUgTTE1LjIsMjMuOUwxNS4yLDIzLjljLTIsMC0zLjksMC40LTUuNywxLjENCgkJCQkJYy0xLjgsMC43LTMuNCwxLjgtNC44LDMuMWMtMC43LDAuNy0xLjQsMS40LTEuOSwyLjJjLTAuNiwwLjgtMS4xLDEuNy0xLjUsMi42Yy0wLjQsMS0wLjcsMi0xLDNDMC4xLDM3LDAsMzguMSwwLDM5LjJ2Mw0KCQkJCQljMCwxLjEsMC4xLDIuMSwwLjMsMy4yYzAuMiwxLDAuNSwyLDEsM2MwLjQsMC45LDAuOSwxLjgsMS41LDIuNmMwLjYsMC44LDEuMiwxLjUsMS45LDIuMmMxLjQsMS4zLDMsMi40LDQuOCwzLjENCgkJCQkJYzEuOCwwLjcsMy43LDEuMSw1LjcsMS4xYzMuMSwwLDYuMS0xLDguNi0yLjdsOC45LTYuMmwxMS4zLTcuOGwtMTEuMy03LjhsLTguOS02LjJDMjEuMywyNC45LDE4LjMsMjMuOSwxNS4yLDIzLjlMMTUuMiwyMy45eiINCgkJCQkJLz4NCgkJCTwvZz4NCgkJCTxnIGlkPSJQYXRoXzI4IiBjbGFzcz0ic3QzIj4NCgkJCQk8cGF0aCBjbGFzcz0ic3Q0IiBkPSJNNTYuMSwyMy45Yy0xLjIsMC0yLjMtMC40LTMuMi0xTDQ0LDE2LjdsOC45LTYuMmMwLjktMC43LDIuMS0xLDMuMi0xYzMuMSwwLDUuNywyLjUsNS43LDUuN2MwLDAsMCwwLDAsMHYzDQoJCQkJCUM2MS44LDIxLjQsNTkuMywyMy45LDU2LjEsMjMuOUM1Ni4xLDIzLjksNTYuMSwyMy45LDU2LjEsMjMuOSBNNTYuMSwzMy41TDU2LjEsMzMuNWMyLDAsMy45LTAuNCw1LjctMS4xDQoJCQkJCWMxLjgtMC43LDMuNC0xLjgsNC44LTMuMWMwLjctMC43LDEuNC0xLjQsMS45LTIuMmMwLjYtMC44LDEuMS0xLjcsMS41LTIuNmMwLjQtMSwwLjctMiwxLTNjMC4yLTEsMC4zLTIuMSwwLjMtMy4ydi0zDQoJCQkJCWMwLTEuMS0wLjEtMi4xLTAuMy0zLjJjLTAuMi0xLTAuNS0yLTEtM2MtMC40LTAuOS0wLjktMS44LTEuNS0yLjZjLTAuNi0wLjgtMS4yLTEuNS0xLjktMi4yYy0xLjQtMS4zLTMtMi40LTQuOC0zLjENCgkJCQkJQzYwLDAuNCw1OC4xLDAsNTYuMSwwYy0zLjEsMC02LjEsMS04LjYsMi43bC04LjksNi4ybC0xMS4zLDcuOGwxMS4zLDcuOGw4LjksNi4yQzUwLDMyLjUsNTMsMzMuNSw1Ni4xLDMzLjVMNTYuMSwzMy41eiIvPg0KCQkJPC9nPg0KCQk8L2c+DQoJPC9nPg0KPC9nPg0KPC9zdmc+DQo=');
        }

        function wp_head()
        {

            $settings = get_option('wotnot-plugin-settings');

            if (is_array($settings) && array_key_exists('script', $settings)) {
                $script = trim($settings['script'], " ");

                // To allow user to add only script tag in head 
                if ($script != '' && preg_match('/^<script/', $script) && preg_match('/script>$/', $script)) {
                    echo $script;
                }
            }
        }

        function wotnot_options_panel()
        {
            // Load options page
            require_once(WOTNOT_PLUGIN_DIR . '/options.php');
        }
    }

    $scripts = new WotNotScriptLoader();
}
