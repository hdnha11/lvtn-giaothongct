INSERT INTO permissions (ID, permKey, permName) VALUES (1, 'quan_tri_nguoi_dung', 'Quản trị người dùng');
INSERT INTO permissions (ID, permKey, permName) VALUES (2, 'lap_bao_cao', 'Lập báo cáo');
INSERT INTO permissions (ID, permKey, permName) VALUES (3, 'cap_nhat_du_lieu', 'Cập nhật dữ liệu');

INSERT INTO roles (ID, roleName) VALUES (1, 'Quản trị hệ thống');
INSERT INTO roles (ID, roleName) VALUES (2, 'Cán bộ chuyên môn');

INSERT INTO role_perms (ID, roleID, permID, "value", addDate) VALUES (1, 1, 1, '1', '2012-3-28 3:18:5');
INSERT INTO role_perms (ID, roleID, permID, "value", addDate) VALUES (2, 1, 2, '1', '2012-3-28 3:18:5');
INSERT INTO role_perms (ID, roleID, permID, "value", addDate) VALUES (3, 1, 3, '1', '2012-3-28 3:18:5');
INSERT INTO role_perms (ID, roleID, permID, "value", addDate) VALUES (4, 2, 2, '1', '2012-3-28 3:18:5');
INSERT INTO role_perms (ID, roleID, permID, "value", addDate) VALUES (5, 2, 3, '1', '2012-3-28 3:18:5');

INSERT INTO users (ID, username, "password") VALUES (1, 'admin', 'admin');
INSERT INTO users (ID, username, "password") VALUES (2, 'canbo1', 'canbo1');
INSERT INTO users (ID, username, "password") VALUES (3, 'canbo2', 'canbo2');


INSERT INTO user_roles (userID, roleID, addDate) VALUES (1, 1, '2012-3-28 3:25:35');
INSERT INTO user_roles (userID, roleID, addDate) VALUES (2, 2, '2012-3-28 3:25:35');
INSERT INTO user_roles (userID, roleID, addDate) VALUES (3, 2, '2012-3-28 3:25:35');