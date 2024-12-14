CREATE TABLE wp_alosec_writing_assistant_keywords (
    id INT AUTO_INCREMENT PRIMARY KEY,
    keyword VARCHAR(255),
    usage_count INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);