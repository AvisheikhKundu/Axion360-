<?php
/**
 * Plugin Name: Simple Banking Plugin
 * Description: A basic plugin to create bank accounts, log in, and transfer money.
 * Version: 1.1
 * Author: Your Name
 */

// Prevent direct access to this file
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Create a database table on plugin activation
function banking_plugin_activate() {
    global $wpdb;
    $table_name = $wpdb->prefix . "bank_accounts";
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        user_id mediumint(9) NOT NULL,
        account_number varchar(20) NOT NULL,
        balance float DEFAULT 0,
        PRIMARY KEY (id),
        UNIQUE KEY account_number (account_number)
    ) $charset_collate;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );
}
register_activation_hook( __FILE__, 'banking_plugin_activate' );

// Register admin menu
function banking_plugin_menu() {
    add_menu_page(
        'Banking Plugin',          // Page title
        'Banking',                 // Menu title
        'manage_options',          // Permissions
        'banking-plugin',          // Menu slug
        'banking_plugin_dashboard' // Callback function to show the dashboard
    );
}
add_action('admin_menu', 'banking_plugin_menu');

// Dashboard Page (for admin)
function banking_plugin_dashboard() {
    echo '<h1>Welcome to the Banking Plugin</h1>';
    echo '<p>Manage user bank accounts and transactions!</p>';
}

// User Registration Form
function banking_plugin_register_form() {
    ob_start();
    ?>
    <form method="post" action="">
        <label for="username">Username:</label>
        <input type="text" name="username" required>
        <label for="email">Email:</label>
        <input type="email" name="email" required>
        <label for="password">Password:</label>
        <input type="password" name="password" required>
        <button type="submit" name="register">Register</button>
    </form>
    <?php
    if ( isset($_POST['register']) ) {
        $username = sanitize_text_field($_POST['username']);
        $email = sanitize_email($_POST['email']);
        $password = sanitize_text_field($_POST['password']);
        $hashed_password = wp_hash_password($password);

        // Create WordPress user
        $user_id = wp_create_user($username, $hashed_password, $email);

        if ( !is_wp_error($user_id) ) {
            // Create a bank account for the user
            global $wpdb;
            $account_number = 'ACC' . rand(100000, 999999);  // Random account number
            $wpdb->insert(
                $wpdb->prefix . 'bank_accounts',
                array(
                    'user_id' => $user_id,
                    'account_number' => $account_number,
                    'balance' => 1000,  // Starting balance
                )
            );
            echo 'Registration successful! Your account number is ' . $account_number;
        } else {
            echo 'Registration failed!';
        }
    }
    return ob_get_clean();
}
add_shortcode('bank_register', 'banking_plugin_register_form');

// User Login Form
function banking_plugin_login_form() {
    ob_start();
    ?>
    <form method="post" action="">
        <label for="username">Username:</label>
        <input type="text" name="username" required>
        <label for="password">Password:</label>
        <input type="password" name="password" required>
        <button type="submit" name="login">Login</button>
    </form>
    <?php
    if ( isset($_POST['login']) ) {
        $username = sanitize_text_field($_POST['username']);
        $password = sanitize_text_field($_POST['password']);
        $user = wp_authenticate($username, $password);

        if ( !is_wp_error($user) ) {
            wp_set_current_user($user->ID);
            wp_set_auth_cookie($user->ID);

            // Fetch bank account details
            global $wpdb;
            $user_id = get_current_user_id();
            $bank_account = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}bank_accounts WHERE user_id = $user_id");

            if ($bank_account) {
                echo 'Login successful! Your account number is ' . $bank_account->account_number . ' with balance: ' . $bank_account->balance;
            } else {
                echo 'No bank account found for your user!';
            }
        } else {
            echo 'Login failed!';
        }
    }
    return ob_get_clean();
}
add_shortcode('bank_login', 'banking_plugin_login_form');

// Money Transfer Form
function banking_plugin_transfer_form() {
    ob_start();

    // Check if user is logged in
    if ( is_user_logged_in() ) {
        ?>
        <form method="post" action="">
            <label for="recipient">Recipient Account Number:</label>
            <input type="text" name="recipient" required>
            <label for="amount">Amount:</label>
            <input type="number" name="amount" required>
            <button type="submit" name="transfer">Transfer</button>
        </form>
        <?php

        if ( isset($_POST['transfer']) ) {
            $recipient_account_number = sanitize_text_field($_POST['recipient']);
            $amount = floatval($_POST['amount']);
            $sender_user_id = get_current_user_id();

            // Get sender's bank account details
            global $wpdb;
            $sender_account = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}bank_accounts WHERE user_id = $sender_user_id");

            if ( $sender_account && $sender_account->balance >= $amount ) {
                // Get recipient's bank account details
                $recipient_account = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}bank_accounts WHERE account_number = '$recipient_account_number'");

                if ( $recipient_account ) {
                    // Update sender's balance
                    $wpdb->update(
                        "{$wpdb->prefix}bank_accounts",
                        array( 'balance' => $sender_account->balance - $amount ),
                        array( 'user_id' => $sender_user_id )
                    );

                    // Update recipient's balance
                    $wpdb->update(
                        "{$wpdb->prefix}bank_accounts",
                        array( 'balance' => $recipient_account->balance + $amount ),
                        array( 'account_number' => $recipient_account->account_number )
                    );

                    echo 'Transfer successful!';
                } else {
                    echo 'Recipient account not found!';
                }
            } else {
                echo 'Insufficient balance or invalid account!';
            }
        }
    } else {
        echo 'Please log in to make a transfer.';
    }

    return ob_get_clean();
}
add_shortcode('bank_transfer', 'banking_plugin_transfer_form');
