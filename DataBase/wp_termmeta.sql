CREATE TABLE wp_termmeta (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    term_id BIGINT NOT NULL,
    meta_key VARCHAR(255),
    meta_value TEXT
);