<?php
    /*
        Plugin Name: SoftwareSeni Team Member
        Description: Objectives - understanding custom post type, post meta, and shortcode details.
        Version: 1.0
        Author: Bismoko Widyatno
    */

    //-- import necessary files for method is_plugin_active
    if( !function_exists( 'is_plugin_active' ) ) {
        include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
    }
    

    /**
     * --------------------------------------------------------------------------
     * Main class for this plugin. This class will handle most of the team member 
     * plugin logic
     * --------------------------------------------------------------------------
     **/
    class SS_Team_Member_Main {
        var $ss_tm_prefix; 
        var $ss_tm_post_type_name;

        function __construct() {
            //-- set prefix & custom post type name
            $this->ss_tm_prefix = "ss_tm_";
            $this->ss_tm_post_type_name = "ss_team_member";

            //-- init custom post types
            add_action( 'init', array( $this, 'ssTmCreateCustomPostType' ) );

            /**
             * execute this when plugin activated and have been loaded
             * 1. show metaboxes
             * 2. register team member shortcode
             **/ 
            add_action( 'plugins_loaded', array( $this, 'ssTmPluginsLoadedHandlers' ) );
        }

        //-- function to create custom post type
        function ssTmCreateCustomPostType() {
            register_post_type( $this->ss_tm_post_type_name,
                array(
                    'labels' => array(
                        'name' => 'Team Members',
                        'singular_name' => 'Team Member',
                        'add_new' => 'Add New',
                        'add_new_item' => 'Add New Team Member',
                        'edit' => 'Edit',
                        'edit_item' => 'Edit Team Member',
                        'new_item' => 'New Team Member',
                        'view' => 'View',
                        'view_item' => 'View Team Member',
                        'search_items' => 'Search Team Members',
                        'not_found' => 'No Team Members Found',
                        'not_found_in_trash' => 'No Team Members Found in Trash',
                        'parent' => 'Parent Team Member'
                    ),
        
                    'public' => true,
                    'menu_position' => 15,
                    'supports' => array( 'title', 'editor', 'comments', 'thumbnail', 'custom-fields' ),
                    'taxonomies' => array( '' ),
                    'menu_icon' => 'dashicons-universal-access',
                    'has_archive' => true
                )
            );
        }

        //-- function to create meta boxes using Meta Box plugin (http://metabox.io/)
        function ssTmCreateMetaBoxes() {
            $ss_meta_boxes[] = array(
                'title'      => 'Other Information',
                'post_types' => $this->ss_tm_post_type_name,
        
                'fields' => array(
                    array(
                        'name'  => esc_html__( 'Position', 'ss_team_member' ),
                        'desc'  => '',
                        'id'    => $this->ss_tm_prefix . 'position',
                        'type'  => 'text',
                    ),
                    array(
                        'name'  => esc_html__( 'Email', 'ss_team_member' ),
                        'desc'  => '',
                        'id'    => $this->ss_tm_prefix . 'email',
                        'type'  => 'email',
                    ),
                    array(
                        'name'  => esc_html__( 'Phone', 'ss_team_member' ),
                        'desc'  => '',
                        'id'    => $this->ss_tm_prefix . 'phone',
                        'type'  => 'number',
                    ),
                    array(
                        'name'  => esc_html__( 'Website', 'ss_team_member' ),
                        'desc'  => esc_html__( 'Example : http://yoursite.com', 'ss_team_member' ),
                        'id'    => $this->ss_tm_prefix . 'website',
                        'type'  => 'url',
                    ),
                    array(
                        'name'  => esc_html__( 'Image', 'ss_team_member' ),
                        'desc'  => '',
                        'id'    => $this->ss_tm_prefix . 'image',
                        'type'  => 'image_advanced',
                    ),
                )
            );

            return $ss_meta_boxes;
        }

        //-- function for creating metaboxes using WP Metabox
        function ssTmCreateMetaBoxesWP() {
            //-- add metabox
            add_meta_box( 
                'ss_team_member_metabox',
                esc_html__( 'Other Information', 'ss_team_member' ),
                array( $this, 'ssTmDisplayWpMetabox' ),
                $this->ss_tm_post_type_name
            );
        }

        //-- function for displaying WP metabox in the team member admin page
        function ssTmDisplayWpMetabox() {
            global $post;

            //-- init meta data variable
            $ss_team_member_data = array();

            //-- nonce field to validate form request
            wp_nonce_field( basename( __FILE__ ), 'ss_team_member' );

            /**
             * get team member meta data if its already entered
             **/
            
            //-- position
            if( empty( $ss_team_member_data[ 'position' ] ) ) {
                $ss_team_member_data[ 'position' ] = get_post_meta( $post->ID, $this->ss_tm_prefix . 'position', true );
            }

            //-- email
            if( empty( $ss_team_member_data[ 'email' ] ) ) {
                $ss_team_member_data[ 'email' ] = get_post_meta( $post->ID, $this->ss_tm_prefix . 'email', true );
            }

            //-- phone
            if( empty( $ss_team_member_data[ 'phone' ] ) ) {
                $ss_team_member_data[ 'phone' ] = get_post_meta( $post->ID, $this->ss_tm_prefix . 'phone', true );
            }

            //-- website
            if( empty( $ss_team_member_data[ 'website' ] ) ) {
                $ss_team_member_data[ 'website' ] = get_post_meta( $post->ID, $this->ss_tm_prefix . 'website', true );
            }

            //-- image
            if( empty( $ss_team_member_data[ 'image' ] ) ) {
                $ss_team_member_data[ 'image' ] = get_post_meta( $post->ID, $this->ss_tm_prefix . 'image', true );
            }
    ?>
            <!-- position -->
            <div>
                <label for="tm-position">Position</label>
                <input type="text" id="tm-position" name="<?php echo $this->ss_tm_prefix . 'position'; ?>" value="<?php echo $ss_team_member_data[ 'position' ]; ?>" />
            </div>
            
            <!-- email -->
            <div>
                <label for="tm-email">Email</label>
                <input type="email" id="tm-email" name="<?php echo $this->ss_tm_prefix . 'email'; ?>" value="<?php echo $ss_team_member_data[ 'email' ]; ?>" />
            </div>
            
            <!-- phone -->
            <div>
                <label for="tm-phone">Phone</label>
                <input type="number" id="tm-phone" name="<?php echo $this->ss_tm_prefix . 'phone'; ?>" value="<?php echo $ss_team_member_data[ 'phone' ]; ?>" />
            </div>
            
            <!-- website -->
            <div>
                <label for="tm-website">Website</label>
                <input type="url" id="tm-website" name="<?php echo $this->ss_tm_prefix . 'website'; ?>" value="<?php echo $ss_team_member_data[ 'website' ]; ?>" />
            </div>

            <!-- image -->
            <?php wp_enqueue_media(); ?>

            <div class='image-preview-wrapper'>
                <?php
                    $ss_tm_image_src = 'https://via.placeholder.com/150';

                    if( !empty( $ss_team_member_data[ 'image' ] ) ) {
                        $ss_tm_image_src = wp_get_attachment_image_src( $ss_team_member_data[ 'image' ] )[ 0 ];
                    }
                ?>
		        <img id='image-preview' src='<?php echo esc_url( $ss_tm_image_src ); ?>' width='100' height='100' style='max-height: 100px; width: 100px;'>
	        </div>
	        <input id="upload_image_button" type="button" class="button" value="<?php _e( 'Upload image' ); ?>" />
	        <input type='hidden' name='<?php echo $this->ss_tm_prefix . 'image'; ?>' id='image_attachment_id' value=''>


    <?php
        }

        //-- function for saving WP Metabox data
        function ssTmSaveWpMetaboxData( $post_id, $post ) {
            //-- create a variable to store the input
            $ss_team_member_data = array();
            $ss_team_member_data_error = array();

            
            //-- return if user doesn't have edit permission
            if ( !current_user_can( 'edit_post', $post_id ) ) {
                return $post_id;
            }

            //-- validating form
            if ( isset( $_POST[ 'ss_team_member' ] ) && !wp_verify_nonce( $_POST[ 'ss_team_member' ], basename(__FILE__) ) ) {
                return $post_id;
            }

            //-- sanitize forms
            if( isset( $_POST[ $this->ss_tm_prefix . 'position' ] ) ) {
                $ss_team_member_data[ $this->ss_tm_prefix . 'position' ] = sanitize_text_field( $_POST[ $this->ss_tm_prefix . 'position' ] );
            }

            if( isset( $_POST[ $this->ss_tm_prefix . 'email' ] ) ) {
                $ss_team_member_data[ $this->ss_tm_prefix . 'email' ] = sanitize_email( $_POST[ $this->ss_tm_prefix . 'email' ] );
            }

            if( isset( $_POST[ $this->ss_tm_prefix . 'phone' ] ) ) {
                $ss_team_member_data[ $this->ss_tm_prefix . 'phone' ] = sanitize_text_field( $_POST[ $this->ss_tm_prefix . 'phone' ] );
            }

            if( isset( $_POST[ $this->ss_tm_prefix . 'website' ] ) ) {
                $ss_team_member_data[ $this->ss_tm_prefix . 'website' ] = sanitize_url( $_POST[ $this->ss_tm_prefix . 'website' ] );
            }

            if( isset( $_POST[ $this->ss_tm_prefix . 'image' ] ) ) {
                $ss_team_member_data[ $this->ss_tm_prefix . 'image' ] = sanitize_text_field( $_POST[ $this->ss_tm_prefix . 'image' ] );
            }

            //-- store the data
            foreach ( $ss_team_member_data as $key => $value ) :
                //-- do not store custom data twice
                if ( 'revision' === $post->post_type ) {
                    return;
                }

                if ( get_post_meta( $post_id, $key, false ) ) {
                    // If the custom field already has a value, update it.
                    update_post_meta( $post_id, $key, $value );
                } else {
                    // If the custom field doesn't have a value, add it.
                    add_post_meta( $post_id, $key, $value);
                }
                if ( !$value ) {
                    // Delete the meta key if there's no value
                    delete_post_meta( $post_id, $key );
                }

            endforeach;
            
        }

        //-- function to create shortcode for displaying the team member
        function ssTmCreateShortcode( $ss_shortcode_atts = array() ) {
            ob_start();

            //-- normalize attribute keys to lowercase
            $ss_shortcode_atts = array_change_key_case( (array)$ss_shortcode_atts, CASE_LOWER );

            //-- override default shortcode parameter
            $ss_team_member_show_info = shortcode_atts([
                                            'to_show' => 'position',
                                        ], $ss_shortcode_atts );
            
            //-- transform shortcode parameter into array
            $ss_team_member_show_info[ 'to_show' ] = explode( ',', $ss_team_member_show_info[ 'to_show' ] );

            //-- get team members
            $ss_args = array(
                'post_type'     => $this->ss_tm_post_type_name,
                'post_status'   => 'publish',
                'orderby'       => 'title',
                'order'         => 'ASC'  
            );

            $ss_team_members = new WP_Query( $ss_args );

            if( $ss_team_members->have_posts() ) {
        ?>

            <ul class="team-member-container">
                <?php
                    while( $ss_team_members->have_posts() ) :
                        $ss_team_members->the_post();
                        $ss_team_members_meta = get_post_meta( get_the_ID() );
                ?>

                    <li>
                        <!-- image -->
                        <?php
                            if( !empty( $ss_team_members_meta[$this->ss_tm_prefix . 'image'][ 0 ] ) ) {
                        ?>
                            <img class="team-members-photo" src="<?php echo esc_url( wp_get_attachment_image_src( $ss_team_members_meta[$this->ss_tm_prefix . 'image'][ 0 ] )[ 0 ] ); ?>" />
                        <?php
                            }
                        ?>
                        
                        <h4 class="team-member-name"><?php echo get_the_title(); ?></h4>
                        
                        <!-- position -->
                        <?php
                            if( !empty( $ss_team_members_meta[$this->ss_tm_prefix . 'position'][ 0 ] ) && in_array( 'position', $ss_team_member_show_info[ 'to_show' ] ) ) {
                        ?>
                            <h6 class="team-member-position"><?php echo $ss_team_members_meta[$this->ss_tm_prefix . 'position'][ 0 ]; ?></h6>
                        <?php
                            }
                        ?>
                        
                        <!-- phone -->
                        <?php
                            if( !empty( $ss_team_members_meta[$this->ss_tm_prefix . 'phone'][ 0 ] ) && in_array( 'phone', $ss_team_member_show_info[ 'to_show' ] ) ) {
                        ?>
                            <h6 class="team-member-phone"><?php echo $ss_team_members_meta[$this->ss_tm_prefix . 'phone'][ 0 ]; ?></h6>
                        <?php
                            }
                        ?>
                        
                        
                        <!-- email -->
                        <?php
                            if( !empty( $ss_team_members_meta[$this->ss_tm_prefix . 'email'][ 0 ] ) && in_array( 'email', $ss_team_member_show_info[ 'to_show' ] ) ) {
                        ?>
                            <h6 class="team-member-email"><?php echo $ss_team_members_meta[$this->ss_tm_prefix . 'email'][ 0 ]; ?></h6>
                        <?php
                            }
                        ?>
                        
                        <!-- website -->
                        <?php
                            if( !empty( $ss_team_members_meta[$this->ss_tm_prefix . 'website'][ 0 ] ) && in_array( 'website', $ss_team_member_show_info[ 'to_show' ] ) ) {
                        ?>
                            <h6 class="team-member-position"><?php echo $ss_team_members_meta[$this->ss_tm_prefix . 'website'][ 0 ]; ?></h6>
                        <?php
                            }
                        ?>
                        
                        <p class="team-member-profile"><?php echo get_the_content(); ?></p>
                    </li>

                <?php
                    endwhile;
                    //-- reset post data
                    wp_reset_postdata();
                ?>
            </ul>

        <?php
            }

            return ob_get_clean();
        }

        //-- function for including media upload scripts
        function ssTmIncludeMediaUploadScript() {
            $my_saved_attachment_post_id = get_option( 'media_selector_attachment_id', 0 );
    ?>

        <script type='text/javascript'>
            jQuery( document ).ready( function( $ ) {
                // Uploading files
                var file_frame;
                var wp_media_post_id = wp.media.model.settings.post.id; // Store the old id
                var set_to_post_id = <?php echo $my_saved_attachment_post_id; ?>; // Set this
                jQuery('#upload_image_button').on('click', function( event ){
                    event.preventDefault();
                    // If the media frame already exists, reopen it.
                    if ( file_frame ) {
                        // Set the post ID to what we want
                        file_frame.uploader.uploader.param( 'post_id', set_to_post_id );
                        // Open frame
                        file_frame.open();
                        return;
                    } else {
                        // Set the wp.media post id so the uploader grabs the ID we want when initialised
                        wp.media.model.settings.post.id = set_to_post_id;
                    }
                    // Create the media frame.
                    file_frame = wp.media.frames.file_frame = wp.media({
                        title: 'Select a image to upload',
                        button: {
                            text: 'Use this image',
                        },
                        multiple: false	// Set to true to allow multiple files to be selected
                    });
                    // When an image is selected, run a callback.
                    file_frame.on( 'select', function() {
                        // We set multiple to false so only get one image from the uploader
                        attachment = file_frame.state().get('selection').first().toJSON();
                        // Do something with attachment.id and/or attachment.url here
                        $( '#image-preview' ).attr( 'src', attachment.url ).css( 'width', 'auto' );
                        $( '#image_attachment_id' ).val( attachment.id );
                        // Restore the main post ID
                        wp.media.model.settings.post.id = wp_media_post_id;
                    });
                        // Finally, open the modal
                        file_frame.open();
                });
                // Restore the main ID when the add media button is pressed
                jQuery( 'a.add_media' ).on( 'click', function() {
                    wp.media.model.settings.post.id = wp_media_post_id;
                });
            });
        </script>

    <?php
        }

        //-- function for executing some task when plugins loaded
        function ssTmPluginsLoadedHandlers() {
            //-- show metaboxes
            if( is_plugin_active( 'meta-box/meta-box.php' ) ) {
                //-- using metabox io
                add_filter( 'rwmb_meta_boxes', array( $this, 'ssTmCreateMetaBoxes' ) );
            } else {
                //-- using WP metabox
                add_action( 'add_meta_boxes', array( $this, 'ssTmCreateMetaBoxesWP' ) );

                //-- action to save WP metabox
                add_action( 'save_post', array( $this, 'ssTmSaveWpMetaboxData' ), 1, 2 );
            }
    
            //-- register team member shortcode
            add_shortcode( 'ss_team_member', array( $this, 'ssTmCreateShortcode' ) );

            //-- add media upload javascript
            add_action( 'admin_footer', array( $this, 'ssTmIncludeMediaUploadScript' ) );
        }
    }


    //-- run the main class
    $ss_team_member_main_class = new SS_Team_Member_Main();
?>

