CREATE TABLE wp_bank_accounts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    account_holder_name VARCHAR(255),
    account_number VARCHAR(20),
    bank_name VARCHAR(100),
    branch_name VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);