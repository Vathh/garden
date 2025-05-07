CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  login VARCHAR(100),
  email VARCHAR(100),
  password VARCHAR(100),
  activation_token VARCHAR(100),
  confirmed BOOLEAN,
  CONSTRAINT UE_User unique (login, email)
);
