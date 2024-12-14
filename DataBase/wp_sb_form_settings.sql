CREATE TABLE wp_sb_form_settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    form_name VARCHAR(255),
    form_settings JSON,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);