CREATE TABLE wp_options (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    option_name VARCHAR(191) NOT NULL,
    option_value LONGTEXT,
    autoload VARCHAR(20) DEFAULT 'yes'
);