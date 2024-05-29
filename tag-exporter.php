<?php
/*
Plugin Name: Tag Exporter
Description: Export tags and their article counts to CSV file
Version: 1.0
Author: Savage Ventures
*/

// Hook function to a custom admin menu
function tag_exporter_menu() {
    add_menu_page('Tag Exporter', 'Tag Exporter', 'manage_options', 'tag_exporter', 'tag_exporter_page');
}

// Callback function for the admin menu page
function tag_exporter_page() {
    // Get all tags
    $tags = get_tags();

    // Create a unique file name
    $file_name = 'tag_export_'.date("j").'.csv';

    // Define the file path
    $upload_dir = wp_get_upload_dir()['basedir'];

    $file_path = $upload_dir . '/tag-exporter/'.$file_name;
    // $file_path = plugin_dir_path(__FILE__) . $file_name;

    unlink($file_path);

    $file = fopen( $file_path, 'w' );

    // CSV file headers.
    $headers = array('Tag','Count','Slug');

    // Write headers.
    fputcsv( $file, $headers );

    fclose( $file );

    $file = fopen( $file_path, 'a' );

    // Loop through each tag
    foreach ($tags as $tag) {

        $name = $tag->name;
        // Remove straight double quotes
        $name = str_replace('" ', '', $name);
        $name = str_replace('"', '', $name);
        // Remove curly double quotes
        $name = str_replace('” ', '', $name);
        $name = str_replace('”', '', $name);
        // Remove curly double quotes
        $name = str_replace('“ ', '', $name);
        $name = str_replace('“', '', $name);

        $slug = get_home_url().'/wp-admin/term.php?taxonomy=post_tag&tag_ID='.$tag->term_id.'&post_type=post'; // slug
        // Wrap tag name in double quotes to handle commas
        $row_data = array($name,$tag->count,$slug);
        fputcsv( $file, $row_data);

    }
    fclose( $file );

    $d_path = get_site_url().'/wp-content/uploads/tag-exporter/' . $file_name;
    // $d_path = get_site_url().'/wp-content/plugins/tag-exporter/' . $file_name;

    // Display the download link
    echo 'File created: <a href="'.$d_path.'" download target="_blank">' . $file_name . '</a>';
}

// Hook the menu function to the admin_menu action
add_action('admin_menu', 'tag_exporter_menu');
?>