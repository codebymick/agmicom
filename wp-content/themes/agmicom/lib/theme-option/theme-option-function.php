<?php
if ( ! function_exists( 'gogo_get_pro_url' ) ) :
	/**
	 * Returns an URL with utm tags
	 * the admin settings page.
	 *
	 * @param string $url    URL fo the site.
	 * @param string $source utm source.
	 * @param string $medium utm medium.
	 * @param string $campaign utm campaign.
	 * @return mixed
	 */
	function gogo_get_pro_url( $url, $source = '', $medium = '', $campaign = '' ){

		$url = trailingslashit( $url );

		// Set up our URL if we have a source.
		if ( isset( $source ) ){
			$url = add_query_arg( 'utm_source', sanitize_text_field( $source ), $url );
		}
		// Set up our URL if we have a medium.
		if ( isset( $medium ) ){
			$url = add_query_arg( 'utm_medium', sanitize_text_field( $medium ), $url );
		}
		// Set up our URL if we have a campaign.
		if ( isset( $campaign ) ){
			$url = add_query_arg( 'utm_campaign', sanitize_text_field( $campaign ), $url );
		}

		return esc_url( $url );
	}

endif;
/**
 * Add admin notice when active theme, just show one time
 *
 * @return bool|null
 */
add_action( 'admin_notices', 'gogo_admin_notice' );
function gogo_admin_notice(){
  global $current_user;
  $user_id   = $current_user->ID;
  $theme_data  = wp_get_theme();
  $gogo_icon    = apply_filters( 'gogo_page_top_icon', true );
  $gogo_nme     = apply_filters( 'gogo_welcome_page_notice_header_site_title','');
  if ( !get_user_meta( $user_id, esc_html( $theme_data->get( 'TextDomain' ) ) . '_notice_ignore' ) ) {
    ?>
    <div class="notice gogo-notice">
      <h1>
        <?php
        if(!$gogo_icon){
        	 printf( esc_html__( 'Thank you for Installing %1$s - Version %2$s', 'gogo' ), esc_html($gogo_nme ), esc_html( $theme_data->Version ) );
        }else{
        /* translators: %1$s: theme name, %2$s theme version */
        printf( esc_html__( 'Thank you for Installing %1$s - Version %2$s', 'gogo' ), esc_html($theme_data->Name), esc_html( $theme_data->Version ) );
        }
        ?>
      </h1>
      <p>
        <?php
        if(!$gogo_icon){
        printf( __( 'Visit %1$s options page to take full advantage of theme.', 'gogo' ), esc_html( $gogo_nme ), esc_url( admin_url( 'themes.php?page=gogo' ) ) );
        printf( '<a href="%1$s" class="notice-dismiss dashicons dashicons-dismiss dashicons-dismiss-icon"></a>', '?' . esc_html( $theme_data->get( 'TextDomain' ) ) . '_notice_ignore=0' );
        }else{
         /* translators: %1$s: theme name, %2$s link */
        printf( __( 'Visit %1$s options page to take full advantage of theme.', 'gogo' ), esc_html( $theme_data->Name ), esc_url( admin_url( 'themes.php?page=gogo' ) ) );
        printf( '<a href="%1$s" class="notice-dismiss dashicons dashicons-dismiss dashicons-dismiss-icon"></a>', '?' . esc_html( $theme_data->get( 'TextDomain' ) ) . '_notice_ignore=0' );	
        }
        ?>
      </p>
      <p>
        <a href="<?php echo esc_url( admin_url( 'themes.php?page=gogo' ) ) ?>" class="button button-primary button-hero" style="text-decoration: none;">
          <?php
          if(!$gogo_icon){
          /* translators: %s theme name */
          printf( esc_html__( 'Get started with %s', 'gogo' ), esc_html( $gogo_nme ) );
          }else{
          /* translators: %s theme name */
          printf( esc_html__( 'Get started with %s', 'gogo' ), esc_html( $theme_data->Name ) );
          }
          ?>
        </a>
      </p>
    </div>
    <?php
  }
}
add_action( 'admin_init', 'gogo_notice_ignore' );
function gogo_notice_ignore(){
  global $current_user;
  $theme_data  = wp_get_theme();
  $user_id   = $current_user->ID;
  /* If user clicks to ignore the notice, add that to their user meta */
  if ( isset( $_GET[ esc_html( $theme_data->get( 'TextDomain' ) ) . '_notice_ignore' ] ) && '0' == $_GET[ esc_html( $theme_data->get( 'TextDomain' ) ) . '_notice_ignore' ] ) {
    add_user_meta( $user_id, esc_html( $theme_data->get( 'TextDomain' ) ) . '_notice_ignore', 'true', true );
  }
}

function gogo_active_plugin(){
        $plugin_slug = 'hunk-companion';
        $active_file_name =  $plugin_slug.'/'.$plugin_slug.'.php';

         if ( is_plugin_active( $active_file_name ) ){
                    $button_class = 'button disabled';
                    $button_txt = esc_html__( 'Plugin & Homepage Activated', 'gogo' );
        }
        if ( ! is_plugin_active( $active_file_name ) ){
                $button_txt = esc_html__( 'Activate Now', 'gogo' );
                $button_class = 'activate-now button-primary '.$plugin_slug;
                $status     = is_dir( WP_PLUGIN_DIR . '/'.$plugin_slug );
                if ( ! $status ){
                  $button_class = 'install-now button button-primary button-large '.$plugin_slug;
                  $button_txt = esc_html__( 'Install Plugin & Setup Homepage', 'gogo' );
                } 

        }

        return "<button data-activated='Plugin & Homepage Activated' data-msg='Activating Plugin & Setup Homepage' data-slug='".esc_attr($plugin_slug)."' data-init='".esc_attr($active_file_name)."' class='".esc_attr( $button_class )."'>{$button_txt}</button>";

}