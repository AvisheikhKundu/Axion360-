CREATE TABLE wp_usermeta (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT NOT NULL,
    meta_key VARCHAR(255),
    meta_value LONGTEXT
);