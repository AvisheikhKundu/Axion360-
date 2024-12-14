CREATE TABLE wp_actionscheduler_actions (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    hook VARCHAR(191),
    status VARCHAR(20),
    scheduled_date DATETIME,
    group_id BIGINT,
    args LONGTEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
