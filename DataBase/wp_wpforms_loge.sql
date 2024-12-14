CREATE TABLE wp_wpforms_loge (
    id INT AUTO_INCREMENT PRIMARY KEY,
    form_id INT NOT NULL,
    log_message TEXT,
    log_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);