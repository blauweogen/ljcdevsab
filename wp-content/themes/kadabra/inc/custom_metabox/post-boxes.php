<?php
/**
 * Include and setup custom metaboxes and fields.
 *
 * @category YourThemeOrPlugin
 * @package  Metaboxes
 * @license  http://www.opensource.org/licenses/gpl-license.php GPL v2.0 (or later)
 * @link     https://github.com/jaredatch/Custom-Metaboxes-and-Fields-for-WordPress
 */

add_filter( 'cmb_meta_boxes', 'cmb_post_metaboxes' );
/**
 * Define the metabox and field configurations.
 *
 * @param  array $meta_boxes
 * @return array
 */
function cmb_post_metaboxes( array $meta_boxes ) {

	$meta_boxes[] = array(

		'id'         => 'post_video_custom_fields',
		'title'      => __('Post Video', 'dfd'),
		'pages'      => array( 'post' ), // Post type
		'context'    => 'normal',
		'priority'   => 'high',
		'show_names' => true, // Show field names on the left
		'fields'     => array(

            array(
                'name' => __('YouTube video ID', 'dfd'),
                'desc'	=> '',
                'id'	=> 'post_youtube_video_url',
                'type'	=> 'text'
            ),
            array(
                'name' =>  __('Vimeo video ID', 'dfd'),
                'desc'	=> '',
                'id'	=> 'post_vimeo_video_url',
                'type'	=> 'text'
            ),
            array(
                'name' =>  __('Self hosted video file in mp4 format', 'dfd'),
                'desc'	=> '',
                'id'	=> 'post_self_hosted_mp4',
                'type'	=> 'file'
            ),
            array(
                'name' =>  __('Self hosted video file in webM format', 'dfd'),
                'desc'	=> '',
                'id'	=> 'post_self_hosted_webm',
                'type'	=> 'file'
            ),
            ),
	);

        
        $meta_boxes[] = array(

            'id'         => 'post_audio_custom_fields',
            'title'      => __('Post Audio', 'dfd'),
            'pages'      => array( 'post' ), // Post type
            'context'    => 'normal',
            'priority'   => 'high',
            'show_names' => true, // Show field names on the left
            'fields'     => array(

                array(
                    'name' =>  __('Use audio embed code', 'dfd'),
                    'desc'	=> '',
                    'id'	=> 'post_custom_post_audio_url',
                    'type'	=> 'text'
                ),
                  array(
                    'name' =>  __('Self hosted audio file in mp3 format', 'dfd'),
                    'desc'	=> '',
                    'id'	=> 'post_self_hosted_audio',
                    'type'	=> 'file'
                ),
            ),
	);
       
   
	// Add other metaboxes as needed

	return $meta_boxes;
}
