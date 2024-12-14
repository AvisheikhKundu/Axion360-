<?php 
/**
 * Chatway clear all cache
 *
 * @author  : Chatway
 * @license : GPLv3
 * */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! function_exists( "chatway_clear_all_caches" ) ) {
    function chatway_clear_all_caches() {
        try {
            global $wp_fastest_cache;
            // if W3 Total Cache is being used, clear the cache
            if ( function_exists( 'w3tc_flush_all' ) ) {
                w3tc_flush_all();

            }
            /* if WP Super Cache is being used, clear the cache */
            if ( function_exists( 'wp_cache_clean_cache' ) ) {
                global $file_prefix, $supercachedir;
                if ( empty( $supercachedir ) && function_exists( 'get_supercache_dir' ) ) {
                    $supercachedir = get_supercache_dir();
                }
                wp_cache_clean_cache( $file_prefix );
            }
            if ( class_exists( 'WpeCommon' ) ) {
                //be extra careful, just in case 3rd party changes things on us
                if ( method_exists( 'WpeCommon', 'purge_memcached' ) ) {
                    //WpeCommon::purge_memcached();
                }
                if ( method_exists( 'WpeCommon', 'clear_maxcdn_cache' ) ) {
                    //WpeCommon::clear_maxcdn_cache();
                }
                if ( method_exists( 'WpeCommon', 'purge_varnish_cache' ) ) {
                    //WpeCommon::purge_varnish_cache();
                }
            }
            /* WP Fastest Cache Plugin */
            if ( method_exists( 'WpFastestCache', 'deleteCache' ) && ! empty( $wp_fastest_cache ) ) {
                $wp_fastest_cache->deleteCache();
            }
            /* WP Rocket Plugin */
            if ( function_exists( 'rocket_clean_domain' ) ) {
                rocket_clean_domain();
                // Preload cache.
                if ( function_exists( 'run_rocket_sitemap_preload' ) ) {
                    run_rocket_sitemap_preload();
                }
            }
            /* Autoptimize Cache Plugin */
            if ( class_exists( "autoptimizeCache" ) && method_exists( "autoptimizeCache", "clearall" ) ) {
                autoptimizeCache::clearall();
            }
            /* LiteSpeed Plugin */
            if ( class_exists( "LiteSpeed_Cache_API" ) && method_exists( "autoptimizeCache", "purge_all" ) ) {
                LiteSpeed_Cache_API::purge_all();
            }
            /* Breeze Plugin */
            if ( class_exists( "Breeze_PurgeCache" ) && method_exists( "Breeze_PurgeCache", "breeze_cache_flush" ) ) {
                Breeze_PurgeCache::breeze_cache_flush();
            }
            /* Hummingbird */
            if ( class_exists( '\Hummingbird\Core\Utils' ) ) {
                $modules = \Hummingbird\Core\Utils::get_active_cache_modules();
                foreach ( $modules as $module => $name ) {
                    $mod = \Hummingbird\Core\Utils::get_module( $module );
                    if ( $mod->is_active() ) {
                        if ( 'minify' === $module ) {
                            $mod->clear_files();
                        } else {
                            $mod->clear_cache();
                        }
                    }
                }
            }
            /* WP Total Cache */
            if ( function_exists( 'wp_cache_clean_cache' ) ) {
                global $file_prefix;
                wp_cache_clean_cache( $file_prefix, true );
            }
        } catch ( Exception $e ) {
            return 1;
        }
    }
}