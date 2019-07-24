<?php
    /*
        Plugin Name: SoftwareSeni Team Member
        Description: Objectives - understanding custom post type, post meta, and shortcode details.
        Version: 1.0
        Author: Bismoko Widyatno
    */


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

            //-- show metaboxes
            add_filter( 'rwmb_meta_boxes', array( $this, 'ssTmCreateMetaBoxes' ) );

            //-- register team member shortcode
            add_shortcode( 'ss_team_member', array( $this, 'ssTmCreateShortcode' ) );
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

        //-- function to create shortcode for displaying the team member
        function ssTmCreateShortcode() {
            ob_start();

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
                            if( !empty( $ss_team_members_meta[$this->ss_tm_prefix . 'position'][ 0 ] ) ) {
                        ?>
                            <h6 class="team-member-position"><?php echo $ss_team_members_meta[$this->ss_tm_prefix . 'position'][ 0 ]; ?></h6>
                        <?php
                            }
                        ?>
                        
                        <!-- phone -->
                        <?php
                            if( !empty( $ss_team_members_meta[$this->ss_tm_prefix . 'phone'][ 0 ] ) ) {
                        ?>
                            <h6 class="team-member-phone"><?php echo $ss_team_members_meta[$this->ss_tm_prefix . 'phone'][ 0 ]; ?></h6>
                        <?php
                            }
                        ?>
                        
                        
                        <!-- email -->
                        <?php
                            if( !empty( $ss_team_members_meta[$this->ss_tm_prefix . 'email'][ 0 ] ) ) {
                        ?>
                            <h6 class="team-member-email"><?php echo $ss_team_members_meta[$this->ss_tm_prefix . 'email'][ 0 ]; ?></h6>
                        <?php
                            }
                        ?>
                        
                        <!-- website -->
                        <?php
                            if( !empty( $ss_team_members_meta[$this->ss_tm_prefix . 'website'][ 0 ] ) ) {
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
    }


    //-- run the main class
    $ss_team_member_main_class = new SS_Team_Member_Main();
?>

