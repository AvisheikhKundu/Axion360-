CREATE TABLE wp_aloseo_posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    post_id INT NOT NULL,
    seo_data JSON,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);