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
            $this->ss_tm_prefix = "ss_tm";
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

            

            return ob_get_clean();
        }
    }


    //-- run the main class
    $ss_team_member_main_class = new SS_Team_Member_Main();
?>

