-- Для импорта в локальном phpMyAdmin на уровне сервера.
CREATE DATABASE IF NOT EXISTS my_blog CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE my_blog;

-- Две связанные таблицы для задания №5.
CREATE TABLE IF NOT EXISTS users (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nickname VARCHAR(80) NOT NULL UNIQUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS articles (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL,
    title VARCHAR(200) NOT NULL,
    body TEXT NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_articles_user FOREIGN KEY (user_id) REFERENCES users(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Примеры. Повторный импорт не создаёт дополнительных записей.
INSERT INTO users (id, nickname) VALUES
(1, 'nastya_dev'),
(2, 'alex_web')
ON DUPLICATE KEY UPDATE nickname = VALUES(nickname);

INSERT INTO articles (id, user_id, title, body) VALUES
(1, 1, 'Добро пожаловать в блог', 'Всем привет, это текст первой статьи.'),
(2, 2, 'Вторая статья', 'Всем привет, это текст второй статьи.')
ON DUPLICATE KEY UPDATE
  user_id = VALUES(user_id), title = VALUES(title), body = VALUES(body);
