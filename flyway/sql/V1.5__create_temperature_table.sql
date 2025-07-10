CREATE TABLE temperature (
  id            BIGINT          AUTO_INCREMENT PRIMARY KEY,
  sensor_id     VARCHAR(100)    NOT NULL,
  value         DECIMAL(5,3)    NOT NULL,
  created_at    DATETIME        DEFAULT CURRENT_TIMESTAMP
);
