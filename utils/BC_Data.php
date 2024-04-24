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
            if($wpdb->query("SHOW TABLES LIKE '".BC__TABLE__PREFIX."';") == 0){
                BC_Data::setupDatabase();
            }
            $opsStr = $wpdb->get_results("SELECT value FROM wp_business_calculator WHERE setting='bcs__options'");
            BC_Data::$options = new BC_Options($opsStr[0]->value);
        }
        public static function saveOptions($postRequest): array | null{
            global $wpdb;
            if(!isset(BC_Data::$options)) BC_Data::init();
            $copyOfOptions = (array) BC_Data::$options;
            array_pop($postRequest);
            foreach($postRequest as $param => $value){
                if(str_contains($param, "_")){
                    // object
                    if(str_starts_with( $param, "input_" )){
                        // input
                        $indexes = explode('_', $param);
                        $last_key = array_pop($indexes);
                        $current = &$copyOfOptions;
                        foreach($indexes as $index){
                            // we can be sure it does exists
                            
                            $current = &$current[$index];
                            $current = (array) $current;
                        }
                        if(str_contains($param, "slider")){
                            $current[$last_key] = ($value == "" ? null : (intval($value) < 0 ? 0 : intval($value)));
                        }elseif($last_key == "industries"){
                            $current[$last_key] = [];
                            //$rows = explode('\n', $value);
                            $rows = preg_split('/\r\n|[\r\n]/', $value);
                            foreach($rows as $row){
                                if(empty($row)) continue;
                                $data = explode('|', $row);
                                $industry = new stdClass();
                                $industry->text = $data[0];
                                $industry->percentage = intval($data[1]);
                                array_push($current[$last_key], $industry);
                            }
                        }elseif($last_key == "buttons"){
                            echo var_export(nl2br($value));
                            $current[$last_key] = [];
                            //$rows = explode('\n', $value);
                            $rows = preg_split('/\r\n|[\r\n]/', $value);
                            foreach($rows as $row){
                                if($row == "") continue;
                                $data = explode('|', $row);
                                $button = new stdClass();
                                $button->text = $data[0];
                                $button->multiplier = floatval($data[1]);
                                array_push($current[$last_key], $button);
                            }
                        }else{
                            $current[$last_key] = ($value == "" ? null : $value);
                        }
                        
                    }else if(str_starts_with($param, "output_")){
                        // output
                        $indexes = explode('_', $param);
                        $last_key = array_pop($indexes);
                        $current = &$copyOfOptions;
                        foreach($indexes as $index){
                            // we can be sure it does exists
                            
                            $current = &$current[$index];
                            $current = (array) $current;
                        }
                        if($last_key == "breakdowns"){
                            $current[$last_key] = [];
                            //$rows = explode('\n', $value);
                            $rows = preg_split('/\r\n|[\r\n]/', $value);
                            foreach($rows as $row){
                                if(empty($row)) continue;
                                $data = explode('|', $row);
                                $breakdown = new stdClass();
                                $breakdown->text = $data[0];
                                $breakdown->percentage = intval($data[1]);
                                array_push($current[$last_key], $breakdown);
                            }
                        }else{
                            $current[$last_key] = ($value == "" ? null : $value);
                        }
                    }
                }else{
                    // simple
                    if(str_contains($param, "currencyOrientation")){
                        $copyOfOptions[$param] = BC_Currency_Orientation::from($value);
                    }else{
                        $copyOfOptions[$param] = ($value == "" ? null : $value);
                    }
                    
                }
            }
            //return $copyOfOptions;
            if(!$wpdb->update(BC__TABLE__PREFIX, array('value' => json_encode($copyOfOptions)), array('setting' => 'bcs__options'))){
                return null;
            }
            return $copyOfOptions;
        }
    }
}
