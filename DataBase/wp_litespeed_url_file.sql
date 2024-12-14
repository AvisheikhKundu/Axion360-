CREATE TABLE wp_litespeed_url_file (
    id INT AUTO_INCREMENT PRIMARY KEY,
    file_path VARCHAR(255),
    file_size INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);