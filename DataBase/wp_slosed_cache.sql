CREATE TABLE wp_slosed_cache (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cache_key VARCHAR(255) NOT NULL,
    cache_value TEXT,
    expiry TIMESTAMP
);