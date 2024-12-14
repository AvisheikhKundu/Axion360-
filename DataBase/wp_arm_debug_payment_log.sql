CREATE TABLE wp_arm_debug_payment_log (
    id INT AUTO_INCREMENT PRIMARY KEY,
    payment_id INT,
    log_message TEXT,
    log_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);