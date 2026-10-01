CREATE DATABASE IF NOT EXISTS campus_equipment;
USE campus_equipment;

CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(100) UNIQUE NOT NULL,
  password VARCHAR(255) NOT NULL,
  role ENUM('student','faculty','staff') NOT NULL DEFAULT 'student'
);

CREATE TABLE equipment (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  category VARCHAR(50),
  quantity INT NOT NULL DEFAULT 1,
  available INT NOT NULL DEFAULT 1,
  description TEXT
);

CREATE TABLE borrowings (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  equipment_id INT NOT NULL,
  quantity INT NOT NULL,
  borrow_date DATE NOT NULL,
  due_date DATE NOT NULL,
  return_date DATE NULL,
  status ENUM('pending','approved','borrowed','returned','rejected') DEFAULT 'pending',
  remarks TEXT,
  FOREIGN KEY (user_id) REFERENCES users(id),
  FOREIGN KEY (equipment_id) REFERENCES equipment(id)
);

INSERT INTO users (name, email, password, role) VALUES
('Staff User', 'staff@example.com', 'staff123', 'staff'),
('Student User', 'student@example.com', 'student123', 'student'),
('Faculty User', 'faculty@example.com', 'faculty123', 'faculty');

INSERT INTO equipment (name, category, quantity, available, description) VALUES
('Projector', 'Electronics', 5, 5, 'Epson Projector'),
('Laptop', 'Electronics', 10, 10, 'Dell Laptop'),
('Microscope', 'Laboratory', 3, 3, 'Binocular Microscope'),
('Camera', 'Multimedia', 2, 2, 'Canon DSLR');