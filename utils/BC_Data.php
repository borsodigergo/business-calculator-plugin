<?php

if (!class_exists("BC_Data")) {
    global $wpdb;
    if (!defined("BC__TABLE__PREFIX")) define("BC__TABLE__PREFIX", $wpdb->prefix . "business_calculator");

    require_once(BC_LOCATION . "/utils/BC_Options.php");
    class BC_Data
    {

        public static BC_Options $options;


        public static function setupDatabase()
        {
            global $wpdb;

            $charset_collate = $wpdb->get_charset_collate();

            $tablename = BC__TABLE__PREFIX;

            $sql = "CREATE TABLE $tablename (
                id mediumint(9) NOT NULL AUTO_INCREMENT,
                setting text NOT NULL,
                value text,
                PRIMARY KEY  (id)
            ) $charset_collate;";

            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
            dbDelta($sql);

            BC_Data::addDefaults();
        }
        private static function addDefaults()
        {
            global $wpdb;
            $wpdb->insert(
                BC__TABLE__PREFIX,
                array(
                    'setting' => "bcs__options",
                    'value' => '{"input": {"industry": {"industries": [{"text": "Consumer Goods","percentage": 10},{"text": "Education","percentage": 10},{"text": "Retail","percentage": 14},{"text": "Technology","percentage": 21},{"text": "Healthcare","percentage": 18},{"text": "Finance","percentage": 10},{"text": "Manufacturing","percentage": 13},{"text": "Real Estate","percentage": 8},{"text": "Hospitality","percentage": 10},{"text": "Transportation","percentage": 10},{"text": "Professional Services","percentage": 15},{"text": "E-Commerce","percentage": 20},{"text": "Entertainment","percentage": 20},{"text": "Nonprofits","percentage": 10},{"text": "Food & Beverages","percentage": 12},{"text": "Energy & Utilities","percentage": 5},{"text": "Other","percentage": 15}]}, "growth": {"buttons": [{"text": "Conservative","multiplier": 1},{"text": "Moderate","multiplier": 1.5},{"text": "Aggressive","multiplier": 2}]}}}'
                )
            );
        }
        public static function init()
        {
            global $wpdb;
            $opsStr = $wpdb->get_results("SELECT value FROM wp_business_calculator WHERE setting='bcs__options'");
            BC_Data::$options = new BC_Options($opsStr[0]->value);
        }
    }
}
