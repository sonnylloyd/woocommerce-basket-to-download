<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://www.linkedin.com/in/sonnylloyduk/
 * @since      1.0.0
 *
 * @package    Woocommerce_Basket_To_Download
 * @subpackage Woocommerce_Basket_To_Download/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Woocommerce_Basket_To_Download
 * @subpackage Woocommerce_Basket_To_Download/public
 * @author     sonny lloyd <echromlog@gmail.com>
 */
class Woocommerce_Basket_To_Download_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Woocommerce_Basket_To_Download_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Woocommerce_Basket_To_Download_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/woocommerce-basket-to-download-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Woocommerce_Basket_To_Download_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Woocommerce_Basket_To_Download_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/woocommerce-basket-to-download-public.js', array( 'jquery' ), $this->version, false );

	}

	public function isUserSubscriber(){
		$user = wp_get_current_user();
		if ( in_array( 'subscriber', (array) $user->roles ) ) {
		    return true;
		}
		return false;
	}

	function wbtd_woocommerce_loop_add_to_cart_link( $html, $product ) {

	  if ( method_exists( $product, 'get_id' ) ) {
	      $product_id = $product->get_id();
	  } else {
	      $product_id = $product->id;
	  }


		$downloads = $product->get_downloads();
		if(count($downloads)<=0){
			return $html;
		}

		if(!$this->isUserSubscriber()){
			return $html;
		}

    $link = get_permalink($product_id);
    $html = '<a href="'.$link.'" class="button alt add_to_cart_button">'.__("Download", "woocommerce").'</a>';

	  return $html;
	}


	function wbtd_price_html( $price, $product ){
			if ( method_exists( $product, 'get_id' ) ) {
				 $product_id = $product->get_id();
			} else {
				 $product_id = $product->id;
			}

			$downloads = $product->get_downloads();
			if(count($downloads) > 0 && $this->isUserSubscriber()){
				return "";
			}

			return $price;
	}

	function wbtd_single_product_custom_button() {
    global $product;

    // WooCommerce compatibility
    if ( method_exists( $product, 'get_id' ) ) {
        $product_id = $product->get_id();
    } else {
        $product_id = $product->id;
    }

		$downloads = $product->get_downloads();

		if(count($downloads) > 0 && $this->isUserSubscriber()){
			//add download link
			echo '<div class="wbtd-download-wrapper">';
			foreach( $downloads as $key => $each_download ) {
				echo '<a target="_blank" class="button alt add_to_cart_button wbtd_single_down_button" href="'.get_site_url(null,'?wbtd_download_image=1&wcpid='.$product_id.'&wciid='.$each_download["id"] ).'">Download</a>';
			}
			echo '</div>';
		}

	}

	function wbtd_download_image(){
		if ( isset( $_GET['wbtd_download_image'] ) ) {

			if(!$this->isUserSubscriber()){
				//header("HTTP/1.0 404 Not Found");
				//return;
				status_header( 404 );
				nocache_headers();
				include( get_query_template( '404' ) );
				die();
			}

			if(empty($_GET['wcpid'])) {
				status_header( 404 );
				nocache_headers();
				include( get_query_template( '404' ) );
				die();
			}
			$prodid = sanitize_text_field($_GET['wcpid']);
			$product = wc_get_product( $prodid );

			if(!$product){
				status_header( 404 );
				nocache_headers();
				include( get_query_template( '404' ) );
				die();
			}

			if(empty($_GET['wciid'])) {
				status_header( 404 );
				nocache_headers();
				include( get_query_template( '404' ) );
				die();
			}
			$downid = sanitize_text_field($_GET['wciid']);

			$downloads = $product->get_downloads();

			if(!$downloads){
				status_header( 404 );
				nocache_headers();
				include( get_query_template( '404' ) );
				die();
			}
			$file = null;
			foreach( $downloads as $key => $each_download ) {
				if($each_download["id"] == $downid){
					$file = $downloads[$key];
					break;
				}
			}

			if(!$file){
				status_header( 404 );
				nocache_headers();
				include( get_query_template( '404' ) );
				die();
			}

			$filetype = $file->get_file_type();
			$filedir = $file->get_file();

			$fp = fopen($filedir, 'rb');

			$filename = $downid.'.'.$file->get_file_extension();

			header("Content-type: " . $filetype);
			header("Content-Disposition: attachment; filename=" . $filename);
			header('Content-Transfer-Encoding: binary');
			header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			fpassthru($fp);
		}

	}

	function wbtd_removing_addtocart_buttons()
	{
	    global $product;

	    // WooCommerce compatibility
	    if ( method_exists( $product, 'get_id' ) ) {
	        $product_id = $product->get_id();
	    } else {
	        $product_id = $product->id;
	    }

			$downloads = $product->get_downloads();
			if(count($downloads) > 0 && $this->isUserSubscriber()){
				remove_action( 'woocommerce_simple_add_to_cart', 'woocommerce_simple_add_to_cart', 30 );
				add_action( 'woocommerce_simple_add_to_cart', array( $this, 'wbtd_single_product_custom_button' ), 30 );
			}

	}

}
