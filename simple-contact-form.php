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
    
    exit;
}

class SimpleContactForm{
    
    public function __construct(){

        //Create Custom Posty Type
        add_action('init', array($this, 'create_custom_post_type'));

        //Add assets like CSS, js files.
        add_action('wp_enqueue_scripts', array($this, 'load_assets'));

        //Add Short Code to add in Gutenberg shortcode component.
        add_shortcode( 'contact-forms', array($this, 'load_shortcode'));

        //Load javaScript
        add_action('wp_footer', array($this, 'load_scripts'));

        //Create-register a REST API with hooks 'rest_api_init'
        add_action('rest_api-init', array($this, 'register_rest_api'));
   

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

    public function load_shortcode(){?>

        <div class="simple-contact-form">
            <h1> Send Us Email </h1>
            <p>Please fill up the form completely</p>

            <form id="simple-contact-form__form">
                    <div class="form-group mb-2">
                            <input type="text" name="name" placeholder="name" class="form-control" />
                    </div>
                    <div class="form-group mb-2">
                            <input type="email" name="email" placeholder="email" class="form-control"  />
                    </div>
                    <div class="form-group mb-2">
                            <input type="tel" name="phone" placeholder="phone" class="form-control" />
                    </div>
                    <div class="form-group mb-2">
                            <textarea placeholder="Enter your message" name="message" class="form-control" ></textarea>
                    </div>
                    <div class="form-group">
                            <button type="submit" class="btn btn-success btn-block w-100">Send Email</button>
                    </div>
            </form> 
        </div>
   <?php }

   public function load_scripts(){?>
        <script>
            //this nonce going to create a number, 
            //which can be attached below headers x-wp-nonce, 
            //so that later on we can verify at backend of wordpress.
            var nonce = '<?php echo wp_create_nonce('wp_rest');?>';

            (function($){
                    $('#simple-contact-form__form').submit(function(event){
                        event.preventDefault();
                        
                        var form = $(this).serialize();

                        //console.log(form);

                        $.ajax({
                            method: 'post',
                            url: '<?php echo get_rest_url(null, 'simple-contact-form/v1/send-email');?>', //get_rest_url gives xyz.com/wp-json/
                            headers: { 'x-wp-nonce': nonce },
                            data: form
                        })                      

                    });

            })(jQuery)

        </script>

   <?php }

   public function register_rest_api(){

        //wp-json/simple-contact-form/v1/send-email -- route

        register_rest_route( 
            'simple-contact-form/v1',
            'send-email',
            array(

                'methods' => 'POST',
                'callback' => array($this, 'handle_contact_form')
            ));
   }
   public function handle_contact_form($data){
        echo "Endpoint is working";
   }
}

new SimpleContactForm;