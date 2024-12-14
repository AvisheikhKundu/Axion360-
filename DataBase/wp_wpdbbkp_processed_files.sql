CREATE TABLE wp_wpdbbkp_processed_files (
    id INT AUTO_INCREMENT PRIMARY KEY,
    file_name VARCHAR(255),
    processed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);