CREATE TABLE wp_terms (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(200),
    slug VARCHAR(200),
    term_group BIGINT
);