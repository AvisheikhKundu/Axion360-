CREATE TABLE wp_wpforms_tasks_mets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    task_name VARCHAR(255),
    status VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
