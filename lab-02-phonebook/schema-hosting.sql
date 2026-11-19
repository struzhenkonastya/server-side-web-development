CREATE TABLE IF NOT EXISTS contacts (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  surname VARCHAR(100) NOT NULL,
  name VARCHAR(100) NOT NULL,
  lastname VARCHAR(100) NOT NULL DEFAULT '',
  gender ENUM('мужской','женский') NOT NULL,
  birth_date DATE NOT NULL,
  phone VARCHAR(40) NOT NULL,
  location VARCHAR(255) NOT NULL,
  email VARCHAR(254) NOT NULL,
  comment TEXT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_full_name (surname, name),
  INDEX idx_birth_date (birth_date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO contacts (surname, name, lastname, gender, birth_date, phone, location, email, comment) VALUES
('Иванов', 'Иван', 'Петрович', 'мужской', '1998-01-12', '+7 900 000-00-01', 'Москва, ул. Мира, 1', 'ivanov@example.com', 'Тестовый контакт 1'),
('Петрова', 'Анна', 'Сергеевна', 'женский', '2001-04-22', '+7 900 000-00-02', 'Казань, ул. Лесная, 2', 'petrova@example.com', 'Тестовый контакт 2'),
('Сидоров', 'Павел', 'Ильич', 'мужской', '1995-11-09', '+7 900 000-00-03', 'Омск, ул. Южная, 3', 'sidorov@example.com', 'Тестовый контакт 3'),
('Кузнецова', 'Мария', 'Алексеевна', 'женский', '2002-07-13', '+7 900 000-00-04', 'Тюмень, ул. Северная, 4', 'kuznetsova@example.com', 'Тестовый контакт 4'),
('Смирнов', 'Артём', 'Денисович', 'мужской', '1989-03-08', '+7 900 000-00-05', 'Пермь, ул. Полевая, 5', 'smirnov@example.com', 'Тестовый контакт 5'),
('Васильева', 'Елена', 'Николаевна', 'женский', '1999-09-30', '+7 900 000-00-06', 'Самара, ул. Новая, 6', 'vasileva@example.com', 'Тестовый контакт 6'),
('Фёдоров', 'Максим', 'Олегович', 'мужской', '1997-12-02', '+7 900 000-00-07', 'Уфа, ул. Центральная, 7', 'fedorov@example.com', 'Тестовый контакт 7'),
('Морозова', 'Ольга', 'Викторовна', 'женский', '2000-05-18', '+7 900 000-00-08', 'Томск, ул. Парковая, 8', 'morozova@example.com', 'Тестовый контакт 8'),
('Волков', 'Дмитрий', 'Михайлович', 'мужской', '1994-10-14', '+7 900 000-00-09', 'Челябинск, пр. Победы, 9', 'volkov@example.com', 'Тестовый контакт 9'),
('Соколова', 'Дарья', 'Романовна', 'женский', '2003-02-26', '+7 900 000-00-10', 'Екатеринбург, ул. Ленина, 10', 'sokolova@example.com', 'Тестовый контакт 10'),
('Орлов', 'Егор', 'Андреевич', 'мужской', '1993-06-16', '+7 900 000-00-11', 'Новосибирск, ул. Школьная, 11', 'orlov@example.com', 'Тестовый контакт 11'),
('Беляева', 'Вера', 'Константиновна', 'женский', '1996-08-05', '+7 900 000-00-12', 'Краснодар, ул. Садовая, 12', 'belyaeva@example.com', 'Тестовый контакт 12');
