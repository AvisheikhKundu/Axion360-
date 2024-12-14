CREATE TABLE wp_posts (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    post_author BIGINT,
    post_date DATETIME,
    post_content LONGTEXT,
    post_title TEXT,
    post_status VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);