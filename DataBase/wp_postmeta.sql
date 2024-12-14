REATE TABLE wp_postmeta (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    post_id BIGINT NOT NULL,
    meta_key VARCHAR(255),
    meta_value LONGTEXT
);