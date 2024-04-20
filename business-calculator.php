<?php 
/**
 * Plugin Name: Business Calculator
 * Description: This is the php plugin version for the javascript module 'BusinessCalculator'
 * Version: 1.0.0
 * Author: Borsodi Gergő
 * License: MIT
 */

if( !defined( 'BC_VER' ) )
    define( 'BC_VER', '1.0.0' );
if(!defined("BC_LOCATION"))
    define("BC_LOCATION", dirname(__FILE__));

if(!class_exists("BusinessCalculator")){

    /**
     * This class is the php plugin wrapper for the javascript module BusinessCalculator
     */
    class BusinessCalculator{
        
        /**
         * We start our class by calling the init() function, which then prepares the class for shortcode usage
         */
        public static function init(){
            if(!BusinessCalculator::checkCoreFile(BC_LOCATION . "/css/all.min.css") || !BusinessCalculator::checkCoreFile(BC_LOCATION . "/css/calculator.css") || !BusinessCalculator::checkCoreFile(BC_LOCATION . "/css/bootstrap.min.css") || !BusinessCalculator::checkCoreFile(BC_LOCATION . "/js/bootstrap.bundle.min.js") || !BusinessCalculator::checkCoreFile(BC_LOCATION . "/pages/bc__settings.php") || !BusinessCalculator::checkCoreFile(BC_LOCATION . "/utils/BC_Data.php")){
                return;
            }
            require_once(BC_LOCATION . "/pages/bc__settings.php");
            require_once(BC_LOCATION . "/utils/BC_Data.php");
            add_action('admin_menu', 'BusinessCalculator::addMenuItems');
            add_action('admin_enqueue_scripts', 'BusinessCalculator::addAdminScripts');
            register_activation_hook( __FILE__, 'BC_Data::setupDatabase' );
            add_shortcode('business-calculator', "BusinessCalculator::displayCalculator");
            
            BC_Data::init();
        }

        public static function addAdminScripts($hook){
            global $bc___settings_page_hook_suffix;
            if( $hook != $bc___settings_page_hook_suffix )
                return;
            wp_register_style('bc__bootstrap',  plugin_dir_url( __FILE__ ) . 'css/bootstrap.min.css');
            wp_enqueue_style('bc__bootstrap');

            wp_register_script('bc__bootstrap_js',  plugin_dir_url( __FILE__ ) . 'js/bootstrap.bundle.min.js');
            wp_enqueue_script('bc__bootstrap_js');
        }

        /**
         * Checks if the provided file exists, and throws an error if not found
         * @param string $file The file's name to check
         * @return bool True if the file exists, false + triggered fatal error otherwise
         */
        private static function checkCoreFile(string $file){
            if(file_exists($file)){
                return true;
            }else{
                trigger_error("[BusinessCalculator] '$file' could not be located, thus the plugin cannot be initialized! ", E_USER_ERROR);
                return false;
            }
        }

        /**
         * Generates the menu items for the plugin
         */
        public static function addMenuItems() { 
            global $bc___settings_page_hook_suffix;
    
            $subPageTitle = "Business Calculator Settings";
            $subMenuLabel = "Business Calculator";
            $subCapability = "edit_dashboard"; //feltetelezzuk h a user az adminisztrator, es csak akkor jelenitjuk meg neki a fizeteses informaciokat
            $subMenuSlug = "business-calculator";
            $subCallbackFunction = "BusinessCalculator::displaySettingsPage";
            //$subIcon = "dashicons-calculator";
            $bc___settings_page_hook_suffix = add_options_page( 
                $subPageTitle, 
                $subMenuLabel, 
                $subCapability, 
                $subMenuSlug, 
                $subCallbackFunction
            );


        }

        public static function displaySettingsPage(){
            BC__Settings::display(BC_Data::$options);
        }
        public static function includeFrontend(){
            wp_enqueue_style ('bc__core_css',  plugin_dir_url( __FILE__ ) . 'css/calculator.css', array());
            wp_enqueue_style ('bc__fontawesome_6',  plugin_dir_url( __FILE__ ) . 'css/all.min.css', array());
            wp_enqueue_style ('bc__bootstrap',  plugin_dir_url( __FILE__ ) . 'css/bootstrap.min.css', array());
        }
        public static function displayCalculator(){
            add_action("wp_enqueue_scripts", "BusinessCalculator::includeFrontend");
            $output = "";
            $output .= '<div class="business-calculator"></div><script src="'.plugin_dir_url( __FILE__ ).'js/calculator.js"></script><script>const BC = new BusinessCalculator({root: document.querySelector("div.business-calculator"),';
            $output .= BusinessCalculator::loopThroughObject(BC_Data::$options);

            $output .= '}); BC.init(); </script>';
            return $output;
        }
        private static function loopThroughObject(mixed $object){
            $output = "";
            if(is_array($object)){
                
                foreach($object as $element){
                    
                    $output .= "{";
                    foreach(get_object_vars($element) as $key => $var){
                        if($var == null) continue;
                        if($var instanceof \BC_Currency_Orientation){
                            $output .= $key . ": '" .$var->value. "',";
                        }elseif(is_array($var)){
                            if(count($var) == 0) continue;
                            $output .= $key. ": [";
                            $output .= BusinessCalculator::loopThroughObject($var);
                            $output .= "],";
                        }elseif(is_object($var)){
                            if(BusinessCalculator::checkAllChildrenNull($var)) continue;
                            $output .= $key. ": {";
                            $output .= BusinessCalculator::loopThroughObject($var);
                            $output .= "},";
                        }else{
                            $output .= $key . ": " .(is_string($var) ? '"'.$var.'"' : $var). ",";
                        }
                    }
                    $output .= "},";
                }
                
            }else{
                foreach(get_object_vars($object) as $key => $var){
                    if($var == null) continue;
                    if($var instanceof \BC_Currency_Orientation){
                        $output .= $key . ": '" .$var->value. "',";
                    }elseif(is_array($var)){
                        if(count($var) == 0) continue;
                        $output .= $key. ": [";
                        $output .= BusinessCalculator::loopThroughObject($var);
                        $output .= "],";
                    }elseif(is_object($var)){
                        if(BusinessCalculator::checkAllChildrenNull($var)) continue;
                        $output .= $key. ": {";
                        $output .= BusinessCalculator::loopThroughObject($var);
                        $output .= "},";
                    }else{
                        $output .= $key . ": " .(is_string($var) ? '"'.$var.'"' : $var). ",";
                    }
                }
            }
            
            return $output;
        }
        private static function checkAllChildrenNull($object){
            foreach(get_object_vars($object) as $key => $var){
                
                if(is_object($var)){
                    if(!BusinessCalculator::checkAllChildrenNull($var)) return false;
                }
                if($var != null) return false;
                
            }
            return true;
        }
    }
    BusinessCalculator::init();
}