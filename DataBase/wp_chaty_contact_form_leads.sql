CREATE TABLE wp_chaty_contact_form_leads (
    id INT AUTO_INCREMENT PRIMARY KEY,
    lead_name VARCHAR(255),
    lead_email VARCHAR(255),
    message TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);