<?php 
/**
 * Plugin Name: Related Posts Plugin
 * Plugin URI: https://example.com
 * Description: A simple plugin to display related posts.
 * Version: 1.0
 * Author: Firoz mahmud
 * Author URI: https://example.com
 * License: GPL2
 */
 if(!defined('ABSPATH')) {
    exit;
 }

 class Wedevs_Related_Posts {

    private static $instance ;


    /**
     * Gets the single instance of the class.
     *
     * @since 0.1.0
     *
     * @access public
     *
     * @return Wedevs_Essential_Security
     */

    public static function get_instance() {
        if(!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

      

    private function __construct() {    
        $this->require_classes();
    }


    /**
     * Requires the necessary classes.
     *
     * @since 0.1.0
     *
     * @access public
     *
     * @return void
     */

    public function require_classes() {
        require_once __DIR__ . '/includes/related-posts.php';

        new Wedevs_Related_Posts_plugin();
        
    }

 }

 Wedevs_Related_Posts::get_instance();
