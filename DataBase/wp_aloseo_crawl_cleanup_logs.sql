CREATE TABLE wp_aloseo_crawl_cleanup_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    log_message TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);