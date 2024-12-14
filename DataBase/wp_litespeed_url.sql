CREATE TABLE wp_litespeed_url (
    id INT AUTO_INCREMENT PRIMARY KEY,
    url VARCHAR(255),
    cache_status VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);