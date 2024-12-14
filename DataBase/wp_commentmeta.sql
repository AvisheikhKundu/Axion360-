CREATE TABLE wp_commentmeta (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    comment_id BIGINT NOT NULL,
    meta_key VARCHAR(255),
    meta_value TEXT
);