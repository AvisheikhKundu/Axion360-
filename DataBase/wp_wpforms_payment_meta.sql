CREATE TABLE wp_wpforms_payment_meta (
    id INT AUTO_INCREMENT PRIMARY KEY,
    payment_id INT NOT NULL,
    meta_key VARCHAR(255),
    meta_value TEXT
);
