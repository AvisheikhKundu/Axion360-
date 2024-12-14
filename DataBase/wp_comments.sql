CREATE TABLE wp_comments (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    post_id BIGINT NOT NULL,
    user_id BIGINT,
    comment_content TEXT,
    comment_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);