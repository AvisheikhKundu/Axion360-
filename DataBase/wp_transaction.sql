CREATE TABLE wp_transaction (
    id INT AUTO_INCREMENT PRIMARY KEY,
    transaction_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    account_id INT NOT NULL,
    amount DECIMAL(15, 2),
    transaction_type VARCHAR(50),
    description TEXT
);