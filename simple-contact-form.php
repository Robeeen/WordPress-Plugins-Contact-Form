<?php
/*
Plugin Name: Simple Contact Form
Version: 1.0
Description: Simple contact form with Rest API & Custom Posty Type
Author: Shams
Author URI: https://shamskhan.xyz
Text Domain: simple-contact-form
*/

if( !defined('ABSPATH'))
{
    echo "Don't be foolish!";
    exit;
}

class SimpleContactForm{
    
    public function __construct(){

        //Create Custom Posty Type
        add_action('init', array($this, 'create_custom_post_type'));

        //Add assets like CSS, js files.
        add_action('wp_enqueue_scripts', array($this, 'load_assets'));
   

    }
    //create custom Post Type
    public function create_custom_post_type(){

        $args = array(
            'public' => true,
            'has_archive' => true,
            'supports' => array('title'),
            'exclude_from_search' => true,
            'publicly_queryable' => false,
            'capability' => 'manage_option',
            'labels' => array(
                'name'=> 'Contact Form',
                'singular_name'=> 'Contact Form Entry'
            ),
            'menu_icon' => 'dashicons-media-text',
        );

        register_post_type('simple_contact_form', $args);
        
    }

    public function load_assets(){
        
            wp_enqueue_style( 
                'simple-contact-form',
                 plugin_dir_url( __FILE__ ) . 'css/simple-contact-form.css',
                 array(),
                 1,
                 'all' );   
            wp_enqueue_script( 
                'simple-contact-form',
                plugin_dir_url( __FILE__ ) . 'js/simple-contact-form.js',
                array('jquery'),
                1,
                true );     
    }


}

new SimpleContactForm;