<?php

/**
Plugin Name: Recommended Links
Plugin URI: https://github.com/matudelatower/wp-recommended-links
Description: Plugin simple para compartir links
Version: 1.0
Author: Matias Solis de la Torre
Author URI: https://github.com/matudelatower/
License: MIT

MIT License

Copyright (c) 2017 Matias Solis de la Torre

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
*/


defined('ABSPATH') or die("Get Lost from here, you idot");


Class comet_recommended_links{

	public function __construct(){



		// settings api
		add_action('admin_init', array($this, 'comet_settings_api'));

		// new menu for api settings
		add_action('admin_menu', array($this, 'recommended_links_menu'));


		// custom metabox
		add_action('add_meta_boxes', array($this, 'braking_news_comet'));

		add_action('save_post', array($this, 'recommended_links_save'));


		// shortcode
		add_shortcode('c_recommended_links', array($this, 'recommended_links_shortcode'));


		// theme css and js files
		add_action('wp_enqueue_scripts', array($this, 'comet_theme_files'));

		// header css
		add_action('wp_head', array($this, 'comet_header_css'));

		// footer js
		add_action('wp_footer', array($this, 'comet_footer_js'));

		// activate post_type: recommended_links
		add_action('init', array($this, 'recommended_links'));



	}


	/**
	 *
	 *
	 *
	 * Settings Api Start Here
	 *
	 *
	 *
	 *
	 */

	public function comet_settings_api(){

		/**
		 *
		 * Title section
		 *
		 *
		 */
		add_settings_section('recommended_links_change_text', 'Título de la sección', array($this,'recommended_links_change_text_cb'), 'recommended_links_slug');


// title text change
		add_settings_field('recommended_links_text', 'Recommended Links Text', array($this, 'b_news_text_cb'), 'recommended_links_slug', 'recommended_links_change_text');

		register_setting('recommended_links_change_text', 'comet_recommended_links_common');


// title text color
		add_settings_field('recommended_links_text_color', 'Color del texto', array($this, 'recommended_links_text_color_cb'), 'recommended_links_slug', 'recommended_links_change_text');

		register_setting('recommended_links_change_text', 'comet_recommended_links_common');


// title text bg-color and
		add_settings_field('recommended_links_text_bg_color', 'Color de fondo', array($this, 'b_text_bg_color'), 'recommended_links_slug', 'recommended_links_change_text');
		register_setting('recommended_links_change_text', 'comet_recommended_links_common');



	}


// section subtitle
	public function recommended_links_change_text_cb(){
		?>

		<p>Bienvenido a la sección de personalizacion de Links</p>

		<?php
	}


// change breaking news title
	public function b_news_text_cb(){
		$options =(array)get_option('comet_recommended_links_common');
		$title = $options['recommended_links_text'];
		?>

		<input type="text" name="comet_recommended_links_common[recommended_links_text]"  class="regular-text" value="<?php echo $title; ?>">

		<?php
	}


// breaking news text color
	public function recommended_links_text_color_cb(){
		$options = (array)get_option('comet_recommended_links_common');
		$text_color = $options['recommended_links_text_color'];
		?>

		<input type="color" name="comet_recommended_links_common[recommended_links_text_color]"  value="<?php echo $text_color; ?>" id="recommended_links_text_color"><br />


		<?php
	}

// breaking news text background
	public function b_text_bg_color(){
		$options = (array)get_option('comet_recommended_links_common');
		$bg_color = $options ['recommended_links_text_bg_color'];
		?>

		<input type="color" name="comet_recommended_links_common[recommended_links_text_bg_color]" id=""  value="<?php echo $bg_color; ?>">

		<?php
	}





	/**
	 *
	 *
	 * menu for news options
	 *
	 *
	 *
	 */
	public function recommended_links_menu(){

		add_submenu_page('edit.php?post_type=comet_news', 'Opciones de Recommended Links', 'Opciones', 'manage_options', 'recommended_links_slug', array($this, 'recommended_links_cb') );

	}

// callback
	public function recommended_links_cb(){
		?>
		<h1 style="text-align:center; color:green; margin:25px 0;">Recommended Links Options</h1>


		<form action="options.php" method="post">

			<?php echo do_settings_sections('recommended_links_slug'); ?>

			<?php echo settings_fields('recommended_links_change_text'); ?>

			<?php echo submit_button(); ?>
		</form>
		<?php echo settings_errors(); ?>
		<?php
	}




	/**
	 *
	 *
	 *
	 * Settings api ends
	 *
	 *
	 *
	 */

	// metabox callback function
	public function braking_news_comet(){
		add_meta_box('braking_news_comet_info', 'Recommended Links', array($this, 'braking_news_comet_cb'), 'comet_news', 'normal');

	}

	// metabox input form
	function braking_news_comet_cb(){

		?>
		<p>
			<label for="news_cat"><strong>Categoría del link :</strong></label> <br />
			<input type="text" name="news_cat" id="news_cat" class="widefat" value="<?php echo get_post_meta( get_the_ID(), 'b_news_cat', true); ?>">
		</p>


		<p>
			<label for="news_content"><strong>Descripcion : </strong></label>
			<input type="text" name="news_content" id="news_content" class="widefat" value="<?php echo get_post_meta( get_the_ID(), 'b_news_content', true); ?> ">

		</p>


		<p>
			<label for="news_link"><strong>Link :</strong></label>
			<input type="url" name="news_link" id="news_link" class="widefat" value="<?php echo get_post_meta( get_the_ID(), 'b_news_link', true); ?>">

		</p>
		<?php
	}

	// breaking news save
	public function recommended_links_save(){

		$news_catagory = $_REQUEST['news_cat'];
		$news_content	= $_REQUEST['news_content'];
		$news_link	= $_REQUEST['news_link'];

		update_post_meta( get_the_ID(), 'b_news_cat', $news_catagory);
		update_post_meta( get_the_ID(), 'b_news_content', $news_content);
		update_post_meta( get_the_ID(), 'b_news_link', $news_link);

	}

	// showing metadata in dashboard




	// breaking news shortcode
	public function recommended_links_shortcode($atts, $content){

		$options = (array)get_option('comet_recommended_links_common');

		$bg_color = $options ['recommended_links_text_bg_color'];
        $extended = isset($atts ['extended']) ? true : false;

        $show_category = isset($atts ['show_category']) ? false : true;

        $title_cat_color = '#000';


		extract( shortcode_atts(array(
			'title_cat_color' => $bg_color,
		), $atts));

		ob_start();
		?>

        <?php

        if( $extended ){


            $q = new WP_Query(array(
                'post_type'	=> 'comet_news',
                'posts_per_page'	=> -1,
            ));
            print "<div>";

            while( $q->have_posts() ):$q->the_post();

                ?>

                    <a href="<?php echo get_post_meta(get_the_id(), 'b_news_link', true); ?>" style="box-shadow: none;" target="_blank">
                        <img class="reclink-favicon" src="https://www.google.com/s2/favicons?domain=<?php echo get_post_meta(get_the_id(), 'b_news_link', true); ?>" alt="<?php echo get_post_meta(get_the_id(), 'b_news_link', true); ?>">
                        <?php if ($show_category) : ?>
                        <span style="color:<?php echo $title_cat_color; ?>;"><?php echo get_post_meta(get_the_ID(), 'b_news_cat', true); ?></span> -
                        <?php endif; ?>
                        <?php echo get_post_meta( get_the_id(), 'b_news_content', true); ?>
                    </a>
                <hr>

                <?php
                endwhile;
                wp_reset_postdata();
            print "</div>";

        }else{
            ?>
            <div class="breakingNews" id="bn4" style="border-color:<?php echo $bg_color; ?>;" >
                <div class="bn-title" style="background-color: <?php echo $bg_color ; ?>;">

                    <h2 style="color:<?php

                    $options = (array)get_option('comet_recommended_links_common');
                    $text_color = $options['recommended_links_text_color'];

                    if( $text_color ){
                        echo $text_color;
                    }else{
                        #fff
                    }

                    ?>;">

                        <?php
                        $options =(array)get_option('comet_recommended_links_common');
                        $title = $options['recommended_links_text'];

                        if( $title ): ?>
                            <?php echo $title; ?>
                        <?php else: ?>
                            Recomendados
                        <?php endif; ?>

                    </h2>
                    <span style="border-color: rgba(0, 0, 0, 0) rgba(0, 0, 0, 0) rgba(0, 0, 0, 0) <?php echo $bg_color; ?>;"></span>
                </div>


                <ul>
                    <?php

                    $q = new WP_Query(array(
                        'post_type'	=> 'comet_news',
                        'posts_per_page'	=> -1,
                    ));

                    while( $q->have_posts() ):$q->the_post();

                        ?>
                        <li>
                            <a href="<?php echo get_post_meta(get_the_id(), 'b_news_link', true); ?>" target="_blank">
                                <img class="reclink-favicon" src="https://www.google.com/s2/favicons?domain=<?php echo get_post_meta(get_the_id(), 'b_news_link', true); ?>" alt="<?php echo get_post_meta(get_the_id(), 'b_news_link', true); ?>">
                                <?php if ($show_category) : ?>
                                    <span style="color:<?php echo $title_cat_color; ?>;"><?php echo get_post_meta(get_the_ID(), 'b_news_cat', true); ?></span> -
                                <?php endif; ?>
                                <?php echo get_post_meta( get_the_id(), 'b_news_content', true); ?></a>
                        </li>

                    <?php endwhile; ?>
                    <?php wp_reset_postdata(); ?>

                </ul>
                <div class="bn-navi">
                    <span></span>
                    <span></span>
                </div>
            </div>
            <?php
        }

        ?>

		<?php  return ob_get_clean();
	}





	// plugin all css and js files
	public function comet_theme_files(){

		/**
		 *
		 * css files
		 *
		 */
		wp_register_style('comet-breakingNews', Plugins_url('/css/breakingNews.css', __FILE__), array(), '1.0.1', 'all');

		wp_register_style('comet-PT-Sans', Plugins_url('//fonts.googleapis.com/css?family=PT+Sans:400,700&subset=latin-ext'), array(), '1.0.2', 'all');

		wp_enqueue_style('comet-breakingNews');
		wp_enqueue_style('comet-PT-Sans');


		/**
		 *
		 * js files
		 *
		 */
		wp_register_script('comet_breakingNews', Plugins_url('/js/breakingNews.js', __FILE__), array('jquery'), '6.0.1', true);

		wp_register_script('comet_custom', Plugins_url('/js/custom.js', __FILE__), array('jquery'), '6.0.2', true);

		wp_enqueue_script('comet_breakingNews');
		wp_enqueue_script('comet_custom');


	}


// header theme css
	public function comet_header_css(){

		$options = (array)get_option('comet_recommended_links_common');
		$bg_color = $options ['recommended_links_text_bg_color'];
		?>

		<style>

			.breakingNews > ul > li > a:hover {
				color: <?php echo $bg_color; ?>;
			}

		</style>


		<?php
	}

// wp footer js

	public function comet_footer_js(){
		?>
		<script>


            jQuery(window).load(function(e) {
                jQuery("#bn4").breakingNews({
                    effect		:"slide-h",
                    autoplay	:true,
                    timer		:5000,
                    // color		:"red"
                });

            });
		</script>

		<?php
	}



	// callback function for post_type: recommended_links
	public function recommended_links(){
		register_post_type('comet_news', array(
			'label'		=> 'Links',
			'labels'	=> array(
				'name'	=> 'Links',
				'add_new'	=> 'Agregar nuevo link',
				'add_new_item'	=> 'Agregar nuevo link'
			),
			'public'	=> true,
			'menu_icon'	=> 'dashicons-megaphone',
			'supports'	=> array('title')
		));

	}

}

$news = new comet_recommended_links();
