INSERT INTO roles (name) VALUES
('admin'),
('user'),
('guest');

INSERT INTO permissions (name) VALUES
('rights_modification'),
('change_app_settings');

INSERT INTO role_permission (role_id, permission_id) VALUES
(1, 1),
(1, 2),
(2, 2);

INSERT INTO users (login, email, password, role_id, confirmed) VALUES
('ciasto', 'ciasto@ciasto.com', 'ciasto123', 1, true);
