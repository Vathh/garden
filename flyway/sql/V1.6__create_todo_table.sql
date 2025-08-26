CREATE TABLE todos (
    id          INT     AUTO_INCREMENT      PRIMARY KEY,
    title       VARCHAR(255)                NOT NULL,
    deadline    DATE                                ,
    is_done     BOOLEAN                     NOT NULL DEFAULT 0,
    created_at  DATETIME                    NOT NULL DEFAULT CURRENT_TIMESTAMP
);