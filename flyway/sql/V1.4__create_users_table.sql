CREATE TABLE users (
  id                            INT AUTO_INCREMENT  PRIMARY KEY,
  login                         VARCHAR(100)        UNIQUE,
  email                         VARCHAR(100)        UNIQUE,
  password                      VARCHAR(100),
  role_id                       INT,
  activation_token              VARCHAR(100),
  activation_token_created_at   DATETIME            DEFAULT CURRENT_TIMESTAMP,
  confirmed                     BOOLEAN
);
