<?php
if ( ! class_exists( 'Gogo_Admin_Settings' ) ){
    /**
	 * Gogo Admin Settings
	 */
	class Gogo_Admin_Settings{
    /**
		 * View all actions
		 *
		 * @since 1.0
		 * @var array $view_actions
		 */
		static public $view_actions = array();

		/**
		 * Menu page title
		 *
		 * @since 1.0
		 * @var array $menu_page_title
		 */
		static public $menu_page_title = 'Gogo Theme';

		/**
		 * Page title
		 *
		 * @since 1.0
		 * @var array $page_title
		 */
		static public $page_title = 'Gogo';

		/**
		 * Plugin slug
		 *
		 * @since 1.0
		 * @var array $plugin_slug
		 */
		static public $plugin_slug = 'gogo';

		/**
		 * Default Menu position
		 *
		 * @since 1.0
		 * @var array $default_menu_position
		 */
		static public $default_menu_position = 'themes.php';

		/**
		 * Parent Page Slug
		 *
		 * @var array $parent_page_slug
		 */
		static public $parent_page_slug = 'general';

		/**
		 * Current Slug
		 *
		 * @var array $current_slug
		 */
		static public $current_slug = 'general';

		/**
		 * Constructor
		 */
		function __construct() {

			if ( ! is_admin() ) {
				return;
			}
			add_action( 'after_setup_theme', __CLASS__ . '::init_admin_settings', 99 );
		}
        /**
		 * Admin settings init
		 */
		static public function init_admin_settings(){
			self::$menu_page_title = apply_filters( 'gogo_menu_page_title', __( 'Gogo Options', 'gogo' ) );
			self::$page_title      = apply_filters( 'gogo_page_title', __( 'Gogo', 'gogo' ) );

			if ( isset( $_REQUEST['page'] ) && strpos( $_REQUEST['page'], self::$plugin_slug ) !== false ) {
				add_action( 'admin_enqueue_scripts', __CLASS__ . '::styles_scripts' );
            
				
			}
			// Let extensions hook into saving.
		    do_action( 'gogo_admin_settings_scripts' );
			self::save_settings();
            add_action( 'admin_enqueue_scripts', __CLASS__ . '::admin_scripts' );
			add_action( 'admin_menu', __CLASS__ . '::add_admin_menu', 99 );

			add_action( 'gogo_menu_general_action', __CLASS__ . '::general_page',99 );
			add_action( 'gogo_header_right_section', __CLASS__ . '::top_header_right_section' );
			add_filter( 'admin_title', __CLASS__ . '::gogo_admin_title', 10, 2 );
			add_action( 'gogo_welcome_page_main_content', __CLASS__ . '::gogo_welcome_page_starter_sites_section', 10 );
			add_action( 'gogo_welcome_page_main_content', __CLASS__ . '::gogo_welcome_page_knowledge_base_scetion', 11 );
            add_action( 'gogo_welcome_page_main_content', __CLASS__ . '::gogo_welcome_page_community_scetion', 12 );
            add_action( 'gogo_welcome_page_main_content', __CLASS__ . '::gogo_welcome_page_five_star_scetion', 13 );
            add_action( 'gogo_welcome_page_main_content', __CLASS__ . '::gogo_recommend_plugins',14 );
			add_action( 'gogo_welcome_page_main_content', __CLASS__ . '::gogo_welcome_page_pro_content',15 );
			add_action( 'gogo_recommend_plugins_setup', __CLASS__ . '::gogo_plugin_setup_api',17);
			// AJAX.
			//add_action( 'wp_ajax_gogo_default_home', __CLASS__ . '::gogo_default_home' );
			add_action( 'wp_ajax_gogo_activeplugin', __CLASS__ . '::gogo_activeplugin' );
		}
		 /**
		 * View actions
		 */
		static public function get_view_actions() {

			if ( empty( self::$view_actions ) ) {

				$actions            = array(
					'general' => array(
						'label' => __( 'Welcome', 'gogo' ),
						'show'  => ! is_network_admin(),
					),
				);
				self::$view_actions = apply_filters( 'gogo_menu_options', $actions );
			}

			return self::$view_actions;
		}
        /**
		 * Save All admin settings here
		 */
		static public function save_settings() {

			// Only admins can save settings.
			if ( ! current_user_can( 'manage_options' ) ){
				return;
			}

			// Let extensions hook into saving.
			do_action( 'gogo_admin_settings_save' );
		}

        /**
		 * Enqueues the needed CSS/JS for the builder's admin settings page.
		 *
		 */
		static public function styles_scripts(){
			// Styles.
			wp_enqueue_style( 'gogo-admin-settings', GOGO_THEME_URI . 'lib/theme-option/assets/css/gogo-admin-menu-settings.css', array(), GOGO_THEME_VERSION );
			// Script.
			wp_enqueue_script( 'gogo-admin-settings', GOGO_THEME_URI . 'lib/theme-option/assets/js/gogo-admin-menu-settings.js', array( 'jquery', 'wp-util', 'updates' ), GOGO_THEME_VERSION );
			

			$localize = array(
				'ajaxUrl'  => admin_url( 'admin-ajax.php' )
			);
			wp_localize_script( 'gogo-admin-settings', 'gogo', apply_filters( 'gogo_theme_js_localize', $localize ) );
		}

		/**
		 * Enqueues the needed CSS/JS for Backend.
		 *
		 */
		static public function admin_scripts(){
			// Styles.
			wp_enqueue_style( 'gogo-admin', GOGO_THEME_URI . 'lib/theme-option/assets/css/gogo-admin.css', array(), GOGO_THEME_VERSION );
			
		}
        /**
		 * Add main menu
		 *
		 */
		static public function add_admin_menu() {

			$parent_page    = self::$default_menu_position;
			$page_title     = self::$menu_page_title;
			$capability     = 'manage_options';
			$page_menu_slug = self::$plugin_slug;
			$page_menu_func = __CLASS__ . '::menu_callback';

			if ( apply_filters( 'gogo_dashboard_admin_menu', true ) ) {
				add_theme_page( $page_title, $page_title, $capability, $page_menu_slug, $page_menu_func );
			} else {
				do_action( 'gogo_register_admin_menu', $parent_page, $page_title, $capability, $page_menu_slug, $page_menu_func );
			}
		}

        /**
		 * Menu callback
		 *
		 */
		static public function menu_callback() {

			$current_slug = isset( $_GET['action'] ) ? esc_attr( $_GET['action'] ) : self::$current_slug;

			$active_tab   = str_replace( '_', '-', $current_slug );
			$current_slug = str_replace( '-', '_', $current_slug );

			$gogo_icon           = apply_filters( 'gogo_page_top_icon', true );
			$ast_visit_site_url = apply_filters( 'gogoa_site_url', 'https://gogo.com' );
			$ast_wrapper_class  = apply_filters( 'gogo_welcome_wrapper_class', array( $current_slug ) );
			$my_theme = wp_get_theme();
			$gogo_theme_version = $my_theme->get( 'Version' );
            
			?>
			<div class="gogo-menu-page-wrapper wrap gogo-clear <?php echo esc_attr( implode( ' ', $ast_wrapper_class ) ); ?>">
					
				<?php do_action( 'gogo_menu_' . esc_attr( $current_slug ) . '_action' ); ?>
			</div>
			<?php
		}
        /**
		 * Include general page
		 *
		 * @since 1.0
		 */
		static public function general_page(){
			get_template_part( 'lib/theme-option/view-general');
		}
        
        /**
		 * Include Welcome page right starter sites content
		 */
		static public function gogo_welcome_page_starter_sites_section(){
			?>
			<div class="postbox">
				<h2 class="hndle gogo-normal-cusror">
					<span class="dashicons dashicons-admin-customizer"></span>
					<span><?php echo esc_html( apply_filters( 'gogo_sites_menu_page_title', __( 'Configure Homepage Layout', 'gogo' ) ) ); ?></span>
				</h2>
				
				<div class="inside">
					<p>
						<?php
							esc_html_e( 'Just one click and your home page is set.  Click the button, it will automatically install and activate plugin as well as Setup Homepage for you', 'gogo' );
						?>
					</p>
						<?php
						echo gogo_active_plugin();
						?>
					<div>
					</div>
				</div>
			</div>

			<?php
		}
		
        /**
		 * Include Welcome page right side knowledge base content
		 */
		static public function gogo_welcome_page_knowledge_base_scetion(){
			?>

			<div class="postbox">
				<h2 class="hndle gogo-normal-cusror">
					<span class="dashicons dashicons-book"></span>
					<span><?php esc_html_e( 'Learn More', 'gogo' ); ?></span>
				</h2>
				<div class="inside">
					<p>
						<?php
						printf(
							esc_html__( 'We want to make sure you have the best experience using %1$s. Visit us and know how it works, take a look and get whole knowledge about %1$s .', 'gogo' ),
							self::$page_title
						);
						?>
					</p>
					<?php
					$gogo_knowledge_base_doc_link      = apply_filters( 'gogo_knowledge_base_documentation_link', gogo_get_pro_url( 'https://themehunk.com/docs/gogo-theme/', 'gogo-dashboard', 'visit-documentation', 'welcome-page' ) );
					$gogo_knowledge_base_doc_link_text = apply_filters( 'gogo_knowledge_base_documentation_link_text', __( 'Visit Us', 'gogo' ) );
					printf(
						'%1$s',
						! empty( $gogo_knowledge_base_doc_link ) ? '<a href=' . esc_url( $gogo_knowledge_base_doc_link ) . ' target="_blank" rel="noopener">' . esc_html( $gogo_knowledge_base_doc_link_text ) . '</a>' :
						esc_html( $gogo_knowledge_base_doc_link_text )
					);
					?>
				</div>
			</div>
			<?php
		}

		/**
		 * Include Welcome page right side Gogo community content
		 */
		static public function gogo_welcome_page_community_scetion(){
			?>
			<div class="postbox">
				<h2 class="hndle gogo-normal-cusror">
					<span class="dashicons dashicons-groups"></span>
					<span>
						<?php
						printf(
							/* translators: %1$s: Gogo Theme name. */
							esc_html__( 'Join Community', 'gogo' ),
							self::$page_title
						);
						?>
				</h2>
				<div class="inside">
					<p>
						<?php
						printf(
							
							esc_html__( 'Join the community of Generous %1$s users. Get connected, share opinion, ask questions and Help each other!', 'gogo' ),
							self::$page_title
						);
						?>
					</p>
					<?php
					$gogo_community_group_link      = apply_filters( 'gogo_community_group_link', 'https://www.facebook.com/pg/ThemeHunk/community/' );
					$gogo_community_group_link_text = apply_filters( 'gogo_community_group_link_text', __('Join us on Facebook', 'gogo' ) );

					printf(
						
						'%1$s',
						! empty( $gogo_community_group_link ) ? '<a href=' . esc_url( $gogo_community_group_link ) . ' target="_blank" rel="noopener">' . esc_html( $gogo_community_group_link_text ) . '</a>' :
						esc_html( $gogo_community_group_link_text )
					);
					?>
				</div>
			</div>
			<?php
		}
        
        /**
		 * Include Welcome page right side Five Star Support
		 *
		 */
		static public function gogo_welcome_page_five_star_scetion(){
			?>
			<div class="postbox">
				<h2 class="hndle gogo-normal-cusror">
					<span class="dashicons dashicons-sos"></span>
					<span><?php esc_html_e( 'Customer Support', 'gogo' ); ?></span>
				</h2>
				<div class="inside">
					<p>
						<?php
						printf(
							
							esc_html__( 'You\'re absolutely free to contact us and ask any question related to our theme. Our tech team will be happy to help you.', 'gogo' ),
							self::$page_title
						);
						?>
					</p>
					<?php
						$gogo_support_link       = apply_filters( 'gogo_support_link', gogo_get_pro_url( 'https://www.themehunk.com/support/', 'submit-a-ticket', 'welcome-page' ) );
						$gogo_support_link_text  = apply_filters( 'gogo_support_link_text', __( 'Submit a Ticket', 'gogo' ) );

						printf(
							/* translators: %1$s: gogo Knowledge doc link. */
							'%1$s',
							! empty( $gogo_support_link ) ? '<a href=' . esc_url( $gogo_support_link ) . ' target="_blank" rel="noopener">' . esc_html( $gogo_support_link_text ) . '</a>' :
							esc_html( $gogo_support_link_text )
						);
					?>
				</div>
			</div>
			<?php
		}
        /**
		 * Include Recommend Plugin
		 *
		 */
		static public function gogo_recommend_plugins(){	
			?>
			<div class="postbox gogo-pro-link">
				<h2 class="hndle gogo-normal-cusror">
					<span class="dashicons dashicons-admin-plugins"></span>
					<span><?php esc_html_e( 'Recommended Plugins', 'gogo' ); ?></span>
				</h2>
				<div class="inside">
					<?php do_action( 'gogo_recommend_plugins_setup' ); ?>
			    </div>
			</div>
			<?php } 
         
		/**
		 * Include Welcome page content
		 *
		 * @since 1.2.4
		 */
		static public function gogo_welcome_page_pro_content(){

			$gogo_addon_tagline = apply_filters( 'gogo_addon_list_tagline', __( 'Get More Options with Gogo Pro!', 'gogo' ) );
			$gogo_visit_pro_feature_site_url = apply_filters( 'gogo_pro_site_url', 'https://wpgogo.com/pro/' );
			
			?>
			<div class="postbox gogo-pro-link">
			
				<h2 class="hndle gogo-normal-cusror">
					<span class="dashicons dashicons-admin-network"></span>
					<span><?php echo esc_html( $gogo_addon_tagline ); ?></span>
				</h2>
					<?php do_action( 'gogo_addon_bulk_action' ); ?>
				</h2>
				<div class="inside">
					<p>
                      <?php
						printf(
							esc_html__( 'Want more features and extended functionalities of %1$s.', 'gogo' ),
							self::$page_title
						);
						?>
                  </p>
                      <?php
						$gogo_pro_link       = apply_filters( 'gogo_support_link', gogo_get_pro_url( 'https://themehunk.com/product/gogo-amazing-wordpress-theme/', 'gogo_pro', 'welcome-page' ) );
						$gogo_pro_link_text  = apply_filters( 'gogo_support_link_text', __( 'Go with Gogo Pro', 'gogo' ) );

						printf(
							/* translators: %1$s: gogo Knowledge doc link. */
							'%1$s',
							! empty( $gogo_pro_link ) ? '<a href=' . esc_url( $gogo_pro_link ) . ' target="_blank" rel="noopener">' . esc_html( $gogo_pro_link_text ) . '</a>' :
							esc_html( $gogo_pro_link_text )
						);
					?>
			    </div>
			</div>

			<?php
		}
		/**
		 * Include Welcome page content
		 */
       static public  function gogo_plugin_setup_api(){
       include_once( ABSPATH . 'wp-admin/includes/plugin-install.php' );
       network_admin_url( 'plugin-install.php' );
       $recommend_plugins = get_theme_support( 'recommend-plugins' );


       if ( is_array( $recommend_plugins ) && isset( $recommend_plugins[0] ) ){
        foreach($recommend_plugins[0] as $slug=>$plugin){
            $plugin_info = plugins_api( 'plugin_information', array(
                       'slug' => $slug,
                    	'fields' => array(
                        'downloaded'        => false,
                        'sections'          => true,
                        'homepage'          => true,
                        'added'             => false,
                        'compatibility'     => false,
                        'requires'          => false,
                        'downloadlink'      => false,
                        'icons'             => false,
                    )
                ) );
                    $plugin_name = $plugin_info->name;
                    $plugin_slug = $plugin_info->slug;
                    $version = $plugin_info->version;
                    $author = $plugin_info->author;
                    $download_link = $plugin_info->download_link;
                   
            

            $status = is_dir( WP_PLUGIN_DIR . '/' . $plugin_slug );
            $active_file_name = $plugin_slug . '/' . $plugin_slug . '.php';
            $button_class = 'install-now button '.$plugin_slug;

             if ( is_plugin_active( $active_file_name ) ) {
                   $button_class = 'button disabled '.$plugin_slug;
                   $button_txt = esc_html__( 'Plugin Activated', 'gogo' );
                   $detail_link = $install_url = '';

        }

            if ( ! is_plugin_active( $active_file_name ) ){
		            $button_txt = esc_html__( 'Install Now', 'gogo' );
		            if ( ! $status ) {
		                $install_url = wp_nonce_url(
		                    add_query_arg(
		                        array(
		                            'action' => 'install-plugin',
		                            'plugin' => $plugin_slug
		                        ),
		                        network_admin_url( 'update.php' )
		                    ),
		                    'install-plugin_'.$plugin_slug
		                );

		            } else {
		                $install_url = add_query_arg(array(
		                    'action' => 'activate',
		                    'plugin' => rawurlencode( $active_file_name ),
		                    'plugin_status' => 'all',
		                    'paged' => '1',
		                    '_wpnonce' => wp_create_nonce('activate-plugin_' . $active_file_name ),
		                ), network_admin_url('plugins.php'));
		                $button_class = 'activate-now button-primary '.$plugin_slug;
		                $button_txt = esc_html__( 'Activate Now', 'gogo' );
		            }
		                $detail_link = add_query_arg(
		                array(
		                    'tab' => 'plugin-information',
		                    'plugin' => $plugin_slug,
		                    'TB_iframe' => 'true',
		                    'width' => '772',
		                    'height' => '349',

		                ),
		                network_admin_url( 'plugin-install.php' )
		            );

                }

				$detail = '';
                echo '<div class="rcp">';
                echo '<h4 class="rcp-name">';
                echo esc_html( $plugin_name );
                echo '</h4>';
				if($plugin_slug=='lead-form-builder'){
				echo'<img src="'.esc_url( GOGO_THEME_URI . 'lib/theme-option/assets/images/lead-form-builder.png' ).'" />'; 
		        $detail='Lead form builder is a contact form as well as lead generator plugin.';
                }elseif($plugin_slug=='hunk-companion'){
                	echo'<img src="'.esc_url( GOGO_THEME_URI . 'lib/theme-option/assets/images/hunk-companion.png' ).'" />'; 
		        $detail= 'Hunk Companion contain all features which are required to create a complete website. Main motive behind this plugin is to boost up functionality of ThemeHunk themes. This plugin will work for Gogo WordPress theme and upcoming ThemeHunk themes.';
                }elseif($plugin_slug=='woocommerce'){
                	echo'<img src="'.esc_url( GOGO_THEME_URI . 'lib/theme-option/assets/images/woocommerce.png' ).'" />'; 
                $detail='WooCommerce is a free eCommerce plugin that allows you to sell anything, beautifully.';
                }elseif($plugin_slug=='elementor'){
                	 echo'<img src="'.esc_url( GOGO_THEME_URI . 'lib/theme-option/assets/images/elementor.png' ).'" />'; 
                $detail='The most advanced frontend drag & drop page builder. Create high-end, pixel perfect websites at record speeds. Any theme, any page, any design.';
                }
			    echo '<p class="rcp-detail">'.esc_html($detail).' </p>';
                echo '<p class="action-btn plugin-card-'.esc_attr( $plugin_slug ).'">
                        <span>Version:'.esc_html($version).'</span>
                        '.$author.'
                        | <a class="plugin-detail thickbox open-plugin-details-modal" href="'.esc_url( $detail_link ).'">'.esc_html__( 'Details', 'gogo' ).'</a>
                </p>';
                echo'<button data-activated="Plugin Activated" data-msg="Activating Plugin" data-init="'.esc_attr($active_file_name).'" data-slug="'.esc_attr( $plugin_slug ).'" class="'.esc_attr( $button_class ).'">'.esc_html($button_txt).'</button>';
                echo '</div>';
        }
    }
}
		/**
		 * Update Admin Title.
		 *
		 * @since 1.0.19
		 *
		 * @param string $admin_title Admin Title.
		 * @param string $title Title.
		 * @return string
		 */
		static public function gogo_admin_title( $admin_title, $title ) {

			$screen = get_current_screen();
			if ( 'appearance_page_gogo' == $screen->id ) {

				$view_actions = self::get_view_actions();

				$current_slug = isset( $_GET['action'] ) ? esc_attr( $_GET['action'] ) : self::$current_slug;
				$active_tab   = str_replace( '_', '-', $current_slug );

				if ( 'general' != $active_tab && isset( $view_actions[ $active_tab ]['label'] ) ) {
					$admin_title = str_replace( $title, $view_actions[ $active_tab ]['label'], $admin_title );
				}
			}

			return $admin_title;
		}

        /**
		 * Gogo Header Right Section Links
		 *
		 * @since 1.2.4
		 */
		static public function top_header_right_section(){

			$top_links = apply_filters(
				'gogo_header_top_links',
				array(
					'gogo-theme-info' => array(
						'title' => __( 'Easy to use, Fully Customizable, Unique options', 'gogo' ),
					),
				)
			);

			if ( ! empty( $top_links ) ) {
				?>
				<div class="gogo-top-links">
					<ul>
						<?php
						foreach ( (array) $top_links as $key => $info ) {
							/* translators: %1$s: Top Link URL wrapper, %2$s: Top Link URL, %3$s: Top Link URL target attribute */
							printf(
								'<li><%1$s %2$s %3$s > %4$s </%1$s>',
								isset( $info['url'] ) ? 'a' : 'span',
								isset( $info['url'] ) ? 'href="' . esc_url( $info['url'] ) . '"' : '',
								isset( $info['url'] ) ? 'target="_blank" rel="noopener"' : '',
								esc_html( $info['title'] )
							);
						}
						?>
						</ul>
					</div>
				<?php
			}
		}
      
		 /*
		  * Plugin install
		  * Active plugin
		  * Setup Homepage
		  */
		public function gogo_activeplugin(){
				$init = isset($_POST['init'])?$_POST['init']:'';
				$slug = isset($_POST['slug']) && $_POST['slug']=='hunk-companion' ?self::gogo_default_home():'';
		        activate_plugin( $init, '', false, true );
			       			wp_die(); 

		}

		static public function gogo_default_home(){
			        $pages = get_pages(array(
			                  'meta_key'    => '_wp_page_template',
			                  'meta_value'  => 'gogo-frontpage.php'
			        ));
			        $post_id = isset($pages[0]->ID)?$pages[0]->ID:false;

			        if(empty($pages)){
			        $post_id = wp_insert_post(array(
			       'post_type'    => 'page',
			       'post_title'   => esc_html__('Home','gogo'),
			       'post_content' => '',
			       'post_name'    => 'gogo-front',
			       'post_status'  => 'publish',
			       'comment_status' => 'closed',   // if you prefer
			       'ping_status'   => 'closed',      // if you prefer
			       'page_template' =>'gogo-frontpage.php', //Sets the template for the page.
			        ));
			       }
			       if($post_id){
			        update_option( 'page_on_front', $post_id );
			        update_option( 'show_on_front', 'page' );
			       }
			       
       }


	}
   new Gogo_Admin_Settings;
}