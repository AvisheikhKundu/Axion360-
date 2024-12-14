CREATE TABLE wp_transfer (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sender_account_id INT NOT NULL,
    receiver_account_id INT NOT NULL,
    amount DECIMAL(15, 2),
    transfer_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);