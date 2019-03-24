<?php
/**
 * View General
 *
 * @package     Gogo
 * @author      Gogo
 * @copyright   Copyright (c) 2018, Gogo
 * @since       Gogo 1.0.0
 */
?>
<div class="gogo-container gogo-welcome">
		<div id="poststuff">
			<div id="post-body" class="columns-1">
				<div id="post-body-content">
					<!-- All WordPress Notices below header -->
					<h1 class="screen-reader-text"><?php esc_html_e( 'Gogo', 'gogo' ); ?> </h1>
						<?php do_action( 'gogo_welcome_page_content_before' ); ?>
                        <div class="goog-content">
						<?php do_action( 'gogo_welcome_page_main_content' ); ?>
                         </div>
						
						<?php do_action( 'gogo_welcome_page_content_after' ); ?>
				</div>
			</div>
			<!-- /post-body -->
			<br class="clear">
		</div>


</div>
