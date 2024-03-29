<?php

function wpbp_register_lib()
{
	if ( is_admin() ) return;

	$wpbp_lib = wpbp_get_lib();

    foreach ( $wpbp_lib as $handle => $lib ) {
        if ( !empty($lib['js']) ) {
            if ( !isset( $lib['deps'] ) )      $lib['deps']      = array();
            if ( !isset( $lib['ver'] ) )       $lib['ver']       = false;
            if ( !isset( $lib['in_footer'] ) ) $lib['in_footer'] = false;
            wpbp_register_script($handle, $lib['js'], $lib['deps'], $lib['ver'], $lib['in_footer']);
        }

        if ( !empty($lib['css']) ) {
            wpbp_register_style($handle, $lib['css']);
        }
    }

    if ( wpbp_get_option('js_files') ) {
        foreach ( ( preg_split('/\r\n|\r|\n/', wpbp_get_option('js_files')) ) as $js_file ) {
            wpbp_add_script( pathinfo($js_file, PATHINFO_FILENAME), $js_file );
        }
    }

    if ( wpbp_get_option('css_files') ) {
        foreach ( ( preg_split('/\r\n|\r|\n/', wpbp_get_option('css_files')) ) as $css_file ) {
            wpbp_add_style( pathinfo($css_file, PATHINFO_FILENAME), $css_file );
        }
    }

    return;
}

function wpbp_register_script($handle, $src = false, $deps = array(), $ver = false, $in_footer = false)
{
	wp_deregister_script($handle);
	wp_register_script($handle, $src, $deps, $ver, $in_footer);
}

function wpbp_add_script($handle, $src = false, $deps = array(), $ver = false, $in_footer = true)
{
	wpbp_register_script($handle, $src, $deps, $ver, $in_footer);
	wp_enqueue_script($handle);
}

function wpbp_register_style($handle, $src = false, $deps = array(), $ver = false, $media = 'all')
{
	wp_deregister_style($handle);
	wp_register_style($handle, $src, $deps, $ver, $media);
}

function wpbp_add_style($handle, $src = false, $deps = array(), $ver = false, $media = 'all')
{
	wpbp_register_style($handle, $src, $deps, $ver, $media);
	wp_enqueue_style($handle, $src, $deps, $ver, $media);
}

function wpbp_enqueue_lib($handles = null)
{
    if ( !is_array( $handles ) && is_string( $handles ) ) $handles = array( $handles );

    $lib = wpbp_get_lib();

    foreach ( $handles as $handle ) {
        if ( isset( $lib[$handle] ) ) {
            if ( isset( $lib[$handle]['js'] ) ) {
                wp_enqueue_script( $handle );
            }
            if ( isset( $lib[$handle]['css'] ) ) {
                wp_enqueue_style( $handle );
            }
        }
    }
}

function wpbp_enqueue_scripts($scripts = array())
{
	if ( is_array( $scripts ) ) {
		foreach ( $scripts as $handle ) {
			wp_enqueue_script($handle);
		}
	}
}

function wpbp_enqueue_styles($styles = array())
{
	if ( is_array( $styles ) ) {
		foreach ( $styles as $handle ) {
			wp_enqueue_style($handle);
		}
	}
}
