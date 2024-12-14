<?php
/**
 * Chatway icons
 *
 * @author  : Chatway
 * @license : GPLv3
 * */

namespace Chatway\App;

class Icons {
    use Singleton;
    
    public static function angel_left() {
        ?>
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                <path d="M12.5 15L7.5 10L12.5 5" stroke="currentColor" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        <?php 
    }

    public static function check_circle() {
        ?>
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
            <rect width="16" height="16" rx="8" fill="#27B836"/>
            <path d="M11.5545 5.3335L6.66558 10.2224L4.44336 8.00016" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        <?php 
    }
}