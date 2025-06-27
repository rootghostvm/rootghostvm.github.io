CREATE DATABASE IF NOT EXISTS ghoststatus;
USE ghoststatus;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE services (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    description TEXT,
    is_monitored BOOLEAN DEFAULT TRUE
);

CREATE TABLE status_updates (
    id INT AUTO_INCREMENT PRIMARY KEY,
    service_id INT,
    status ENUM('operational', 'degraded', 'outage', 'maintenance') NOT NULL,
    message TEXT NOT NULL,
    user_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (service_id) REFERENCES services(id),
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Default admin (password: admin123 - change this!)
INSERT INTO users (username, password) VALUES (
    'admin',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'
);

-- Default services
INSERT INTO services (name, description) VALUES
    ('FiveM', 'FiveM Game Servers'),
    ('Minecraft', 'Minecraft Hosting'),
    ('Discord Bots', 'Discord Bot Hosting'),
    ('Website', 'Main Website and API');
