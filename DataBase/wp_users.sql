CREATE TABLE wp_users (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    user_login VARCHAR(60),
    user_pass VARCHAR(255),
    user_nicename VARCHAR(50),
    user_email VARCHAR(100),
    user_registered TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);