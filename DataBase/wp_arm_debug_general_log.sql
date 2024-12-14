CREATE TABLE wp_arm_debug_general_log (
    id INT AUTO_INCREMENT PRIMARY KEY,
    log_message TEXT,
    log_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);