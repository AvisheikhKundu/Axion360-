CREATE TABLE wp_term_relationshipe (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    object_id BIGINT NOT NULL,
    term_taxonomy_id BIGINT NOT NULL,
    term_order INT
);