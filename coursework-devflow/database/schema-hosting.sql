CREATE TABLE IF NOT EXISTS users (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  nickname VARCHAR(80) NOT NULL,
  email VARCHAR(191) UNIQUE DEFAULT NULL,
  password_hash VARCHAR(255) DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS articles (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNSIGNED NOT NULL,
  slug VARCHAR(150) NOT NULL UNIQUE,
  category VARCHAR(60) NOT NULL,
  minutes INT UNSIGNED NOT NULL DEFAULT 5,
  title VARCHAR(200) NOT NULL,
  summary TEXT NOT NULL,
  body TEXT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_articles_author FOREIGN KEY (user_id) REFERENCES users(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS tasks (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNSIGNED NOT NULL,
  title VARCHAR(160) NOT NULL,
  priority ENUM('low','medium','high') NOT NULL DEFAULT 'medium',
  completed TINYINT(1) NOT NULL DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_tasks_user_status (user_id, completed),
  CONSTRAINT fk_tasks_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS estimates (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNSIGNED NOT NULL,
  project_type VARCHAR(40) NOT NULL,
  pages SMALLINT UNSIGNED NOT NULL,
  integrations TINYINT UNSIGNED NOT NULL,
  total_price INT UNSIGNED NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_estimate_user (user_id),
  CONSTRAINT fk_estimate_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS feedback (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(191) NOT NULL,
  subject VARCHAR(100) NOT NULL,
  message TEXT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Авторы-демонстраторы: у них нет паролей, поэтому вход через эти записи невозможен.
INSERT INTO users (id, name, nickname, email, password_hash) VALUES
(1,'Анастасия','nastya_dev',NULL,NULL),
(2,'Александр','alex_web',NULL,NULL)
ON DUPLICATE KEY UPDATE nickname=VALUES(nickname);

INSERT INTO articles (id, user_id, slug, category, minutes, title, summary, body) VALUES
(1,1,'php-routing','Backend',7,'Как устроена маршрутизация в PHP','Разбираем путь запроса от адресной строки до метода контроллера.','Маршрутизация определяет, какой код выполнится при открытии URL. Фреймворк Flight связывает адрес и HTTP-метод с функцией контроллера. Это позволяет отделять логику от оформления и поддерживать приложение по мере роста.\n\nВ DevFlow маршруты объявлены в public/index.php. Например, URL /articles передаётся методу ArticleController::index(), а адрес конкретной статьи — методу show().\n\nТакой подход мы изучали в лабораторных заданиях, а в курсовой применили на практике.'),
(2,2,'mysql-relations','Базы данных',9,'Связи MySQL: от статьи к автору','Почему удобнее хранить авторов и статьи в разных таблицах.','Реляционная база данных хранит сведения об авторах и публикациях независимо. Каждая статья содержит user_id, который ссылается на первичный ключ таблицы users.\n\nПри открытии статьи мы выполняем отдельный запрос SELECT nickname FROM users WHERE id = ?. Так сайт получает актуальный псевдоним автора, не дублируя его в каждой статье.\n\nДля запросов применяется PDO с подготовленными выражениями. Это помогает избегать SQL-инъекций.'),
(3,1,'ui-design','Интерфейсы',6,'От макета до интерфейса: основы UI','Типографика, сетки, иерархия и удобные формы.','Хороший интерфейс начинается с ясной визуальной иерархии. На главной странице важные действия выделены сильнее второстепенных; карточки задач и статей используют повторяемую сетку.\n\nДля адаптивности применяются CSS Grid и Flexbox. На маленьком экране навигация меняет расположение, а формы остаются удобными для ввода.\n\nСостояния кнопок, фокус полей и информативные сообщения об ошибках повышают доступность.'),
(4,2,'security-web','Безопасность',8,'Безопасность форм: CSRF, XSS и хеширование','Что обязательно предусмотреть при работе с пользовательскими данными.','В DevFlow формы, изменяющие данные, используют CSRF-токен. Перед сохранением значения проходят серверную проверку, а выводимые строки экранируются htmlspecialchars.\n\nПароли пользователей сохраняются только как хеши password_hash и сравниваются функцией password_verify. Запросы выполняются через подготовленные выражения PDO.\n\nКонфигурация базы данных лежит вне публичного каталога, а секретный файл исключён из Git.');
