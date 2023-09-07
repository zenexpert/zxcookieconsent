<?php
// -----
// Admin-level initialization script for the ZX Optimized Images plugin by ZenExpert.
// Copyright (C) 2020, ZenExpert
//
if (!defined('IS_ADMIN_FLAG')) {
    die('Illegal Access');
}

define('ZX_COOKIE_CONSENT_CURRENT_VERSION', '1.0.0');

// -----
// Only update configuration when an admin is logged in.
//
if (isset($_SESSION['admin_id'])) {
    if (!defined('ZX_COOKIE_CONSENT_VERSION')) {
        $configurationGroupTitle = 'ZX Cookie Consent';
        $configuration = $db->Execute(
            "SELECT configuration_group_id 
           FROM " . TABLE_CONFIGURATION_GROUP . " 
          WHERE configuration_group_title = '$configurationGroupTitle' 
          LIMIT 1"
        );
        if ($configuration->EOF) {
            $db->Execute(
                "INSERT INTO " . TABLE_CONFIGURATION_GROUP . " 
                (configuration_group_title, configuration_group_description, sort_order, visible) 
            VALUES 
                ('$configurationGroupTitle', '$configurationGroupTitle', '1', '1')"
            );
            $cgi = $db->Insert_ID();
            $db->Execute("UPDATE " . TABLE_CONFIGURATION_GROUP . " SET sort_order = $cgi WHERE configuration_group_id = $cgi;");
        } else {
            $cgi = $configuration->fields['configuration_group_id'];
        }

        // ----
        // If not already set, record the configuration's current version in the database.
        //

        $db->Execute(
            "INSERT INTO " . TABLE_CONFIGURATION . "
                (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, date_added, sort_order, set_function)
             VALUES
                ('ZX Cookie Consent Version', 'ZX_COOKIE_CONSENT_VERSION', '1.0.0', 'Currently installed version of ZX Cookie Consent.<br />Module brought to you by <a href=\"https://zenexpert.com\" target=\"_blank\">ZenExpert</a>', $cgi, now(), 10, 'trim(')"
        );

        $db->Execute(
            "INSERT INTO " . TABLE_CONFIGURATION . " 
                ( configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, use_function, set_function ) 
             VALUES 
                ( 'ZX Cookie Consent Enabled', 'ZX_COOKIE_CONSENT_ENABLED', 'off', 'Enable ZX Cookie Consent', $cgi, 20, now(), NULL, 'zen_cfg_select_option(array(\'on\', \'off\'),')"
        );

        $db->Execute(
            "INSERT INTO " . TABLE_CONFIGURATION . " 
                ( configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, use_function, set_function ) 
             VALUES 
                ( 'Custom message', 'ZX_COOKIE_CONSENT_MESSAGE', 'This website uses cookies to ensure you get the best experience on our website.', 'Message displayed on the Cookie Consent bar', $cgi, 30, now(), NULL, NULL)"
        );

        $db->Execute(
            "INSERT INTO " . TABLE_CONFIGURATION . " 
                ( configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, use_function, set_function ) 
             VALUES 
                ( 'Dismiss button text', 'ZX_COOKIE_CONSENT_DISMISS_BUTTON_TEXT', 'Got it!', 'Dismiss button text', $cgi, 40, now(), NULL, NULL)"
        );

        $db->Execute(
            "INSERT INTO " . TABLE_CONFIGURATION . " 
                ( configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, use_function, set_function ) 
             VALUES 
                ( 'Learn more text', 'ZX_COOKIE_CONSENT_LEARN_MORE_TEXT', 'Learn more', 'Policy link text', $cgi, 50, now(), NULL, NULL)"
        );

        $db->Execute(
            "INSERT INTO " . TABLE_CONFIGURATION . " 
                ( configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, use_function, set_function ) 
             VALUES 
                ( 'Learn more link', 'ZX_COOKIE_CONSENT_LEARN_MORE_LINK', 'index.php?main_page=privacy', 'Policy link', $cgi, 60, now(), NULL, NULL)"
        );

        $next_sort = $db->Execute("SELECT MAX(sort_order) as max_sort FROM " . TABLE_ADMIN_PAGES . " WHERE menu_key='customers'", false, false, 0, true);
        zen_register_admin_page('configZXCookieConsent', 'BOX_CONFIGURATION_ZX_COOKIE_CONSENT', 'FILENAME_CONFIGURATION', "gID=$cgi", 'configuration', 'Y', $cgi);

        $messageStack->add('ZX Cookie Consent installed. Please enable it from Configuration->ZX Cookie Consent menu.', 'success');
    }

}