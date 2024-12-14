<?php
/*
Plugin Name: Custom Money Transfer
Description: Allows users to transfer custom money between each other.
Version: 1.0
Author: Robiul Hasan Nafis
*/

// Hook to initialize the plugin
function cmt_plugin_init() {
    // Add custom money field to user profile on registration
    add_user_meta(get_current_user_id(), 'custom_money', 0, true);

    // Shortcode to display transfer form
    add_shortcode('cmt_transfer_form', 'cmt_transfer_form_shortcode');
}
add_action('init', 'cmt_plugin_init');

// Shortcode function to render transfer form
function cmt_transfer_form_shortcode() {
    if (is_user_logged_in()) {
        ob_start(); ?>
        <form method="post">
            <label for="recipient">Recipient Username:</label>
            <input type="text" name="recipient" required>
            <label for="amount">Amount:</label>
            <input type="number" name="amount" required min="1">
            <button type="submit" name="cmt_transfer_money">Transfer Money</button>
        </form>
        <?php
        // Process transfer when form is submitted
        if (isset($_POST['cmt_transfer_money'])) {
            cmt_process_transfer($_POST['recipient'], $_POST['amount']);
        }
        return ob_get_clean();
    } else {
        return 'You must be logged in to transfer money.';
    }
}

// Process the transfer logic
function cmt_process_transfer($recipient_username, $amount) {
    if ($amount <= 0) {
        echo 'Invalid amount.';
        return;
    }

    $sender_id = get_current_user_id();
    $sender_money = get_user_meta($sender_id, 'custom_money', true);
    
    // Find the recipient by username
    $recipient = get_user_by('login', $recipient_username);
    if (!$recipient) {
        echo 'Recipient not found.';
        return;
    }
    
    $recipient_id = $recipient->ID;
    $recipient_money = get_user_meta($recipient_id, 'custom_money', true);

    // Ensure the sender has enough money
    if ($sender_money < $amount) {
        echo 'Insufficient funds.';
        return;
    }

    // Perform the transfer
    update_user_meta($sender_id, 'custom_money', $sender_money - $amount);
    update_user_meta($recipient_id, 'custom_money', $recipient_money + $amount);

    echo 'Transfer successful!';
}
function cmt_display_balance_shortcode() {
    if (is_user_logged_in()) {
        $user_id = get_current_user_id();
        $balance = get_user_meta($user_id, 'custom_money', true);
        return "Your current balance is: $" . ($balance ? $balance : 1000);
    } else {
        return "You must be logged in to view your balance.";
    }
}
add_shortcode('cmt_display_balance', 'cmt_display_balance_shortcode');
?>

