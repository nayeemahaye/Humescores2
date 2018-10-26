<?php
/**
 * Jetpack Compatibility File.
 *
 * @link https://jetpack.com/
 *
 * @package Calafate
 */


/** Remove JetPack's Photon, which interferes with the theme's built-in resizing functions
 *
 * @since 1.0.0
*/

if ( class_exists( 'Jetpack' ) && Jetpack::is_module_active( 'photon' ) ) :

	$photon_removed = remove_filter( 'image_downsize', array( Jetpack_Photon::instance(), 'filter_image_downsize' ) );

	if ( $photon_removed )
		add_filter( 'image_downsize', array( Jetpack_Photon::instance(), 'filter_image_downsize' ), 10, 3 );

endif;


