CREATE TABLE wp_term_taxonomy (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    term_id BIGINT NOT NULL,
    taxonomy VARCHAR(32),
    description LONGTEXT,
    parent BIGINT,
    count BIGINT
);