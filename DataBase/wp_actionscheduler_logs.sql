CREATE TABLE wp_actionscheduler_logs (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    action_id BIGINT NOT NULL,
    message TEXT,
    log_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);