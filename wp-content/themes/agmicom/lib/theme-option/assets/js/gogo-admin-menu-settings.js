(function($){

    GogoAdmin = {

        init: function(){
            this._bind();
        },

        _installNow: function( event ) {
             $document   = jQuery(document);
              var slug = $(this).data('slug'); 
            var $message = $( '.install-now.'+slug);

                if ( wp.updates.shouldRequestFilesystemCredentials && ! wp.updates.ajaxLocked ) {
                    wp.updates.requestFilesystemCredentials( event );
                    $document.on( 'credential-modal-cancel', function() {
                        var $message = $( '.install-now' );

                        $message.text( wp.updates.l10n.installNow );

                        wp.a11y.speak( wp.updates.l10n.updateCancel, 'polite' );
                    } );
                }
                 wp.updates.installPlugin( {
                    slug:  $message.data('slug'),
                    init:  $message.data('init'),
                } );
        },
        /*
         * Plugin Installation Error.
         */
        _installError: function( event, response ) {
            var $card = jQuery( '.install-now');
            $card.removeClass( 'button-primary' )
                .addClass( 'disabled' )
                .html( wp.updates.l10n.installFailedShort );
                    console.log(response.errorMessage);
        },

        /**
         * Installing Plugin
         */
        _pluginInstalling: function(event, args) {
            event.preventDefault();
            var $card = jQuery( '.'+args.slug);
            var $button = $card.find( '.button-primary' );
            $button.removeClass( 'install-now button-primary installed button-disabled updated-message' )

            $card.addClass('updating-message').html('Installing Plugin');
            $button.addClass('already-started');
        },

        /**
         * Plugin activation
         */
        _activetedPlugin: function(event, args) {
                event.preventDefault();
                var $card = jQuery( '.'+args.slug);
                GogoAdmin._activePluginHomepage(args.slug,$card.data('init'));
        },

        /**
         * Plugin & Homepage Activation
         */
        _activePluginHomepage: function($slug,$init){
            var $message = jQuery( '.'+$slug);
               $message.removeClass( 'install-now button-primary installed button-disabled updated-message' )
                .addClass('updating-message')
                .html($message.data('msg'));

            $.ajax({
                url  : gogo.ajaxUrl,
                type : 'POST',
                data : {
                    action : 'gogo_activeplugin',
                    init   :  $init,
                    slug   :  $slug
                },
                beforeSend: function() {
                }
            })
            .fail(function( jqXHR ){
            })
            .done(function ( response ) {
                $message.removeClass( 'button-primary install-now activate-now updating-message' )
                            .attr('disabled', 'disabled')
                            .addClass('disabled')
                            .text($message.data('activated'));
                            if($slug=='hunk-companion'){
                            window.location.href = 'customize.php';
                            }
            });
        },

        /**
         * Plugin activation
         */
        _activePlugin: function(event) {
                var $button = jQuery( event.target ),
                $init   = $button.data( 'init' ),
                $slug   = $button.data( 'slug' );
                GogoAdmin._activePluginHomepage($slug,$init);
            },
        _bind: function(){               
            $( document ).on('click'                     , '.install-now', GogoAdmin._installNow);
            $( document ).on('click'                     , '.activate-now', GogoAdmin._activePlugin);
            $( document ).on('wp-plugin-install-error'   , GogoAdmin._installError);
            $( document ).on('wp-plugin-installing'      , GogoAdmin._pluginInstalling);
            $( document ).on('wp-plugin-install-success' , GogoAdmin._activetedPlugin);  
        },


};

GogoAdmin.init();

})(jQuery);