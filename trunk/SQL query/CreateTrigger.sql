/* Khong cho delete cap_duong co id = 0 */
CREATE OR REPLACE FUNCTION check_del_cap_duong() RETURNS trigger AS $check_del_cap_duong$
    BEGIN            
        IF OLD.id_cap = 0 THEN
            RAISE EXCEPTION 'Khong the xoa cap duong mac dinh';
        END IF;
        RETURN OLD;
    END;
$check_del_cap_duong$ LANGUAGE plpgsql;

CREATE TRIGGER check_del_cap_duong BEFORE DELETE ON cap_duong /*table name*/
    FOR EACH ROW EXECUTE PROCEDURE check_del_cap_duong();

/* Khong cho delete loai_duong co id = 0 */
CREATE OR REPLACE FUNCTION check_del_loai_duong() RETURNS trigger AS $check_del_loai_duong$
    BEGIN            
        IF OLD.id_loai IN (0, 1, 2) THEN
            RAISE EXCEPTION 'Khong the xoa loai duong mac dinh';
        END IF;
        RETURN OLD;
    END;
$check_del_loai_duong$ LANGUAGE plpgsql;

CREATE TRIGGER check_del_loai_duong BEFORE DELETE ON loai_duong /*table name*/
    FOR EACH ROW EXECUTE PROCEDURE check_del_loai_duong();

/* Khong cho delete co_quan_quan_ly co id = 0 */
CREATE OR REPLACE FUNCTION check_del_cqql() RETURNS trigger AS $check_del_cqql$
    BEGIN            
        IF OLD.id_co_quan = 0 THEN
            RAISE EXCEPTION 'Khong the xoa co quan quan ly mac dinh';
        END IF;
        RETURN OLD;
    END;
$check_del_cqql$ LANGUAGE plpgsql;

CREATE TRIGGER check_del_cqql BEFORE DELETE ON co_quan_quan_ly /*table name*/
    FOR EACH ROW EXECUTE PROCEDURE check_del_cqql();

/*----------- Phan trigger cho quan ly phan quyen ------------*/
/* Khong cho delete default users */
CREATE OR REPLACE FUNCTION check_del_users() RETURNS trigger AS $check_del_users$
    BEGIN            
        IF OLD."default" = TRUE THEN
            RAISE EXCEPTION 'Khong the xoa nguoi dung mac dinh';
        END IF;
        RETURN OLD;
    END;
$check_del_users$ LANGUAGE plpgsql;

CREATE TRIGGER check_del_users BEFORE DELETE ON users /*table name*/
    FOR EACH ROW EXECUTE PROCEDURE check_del_users();

/* Khong cho delete default permissions */
CREATE OR REPLACE FUNCTION check_del_permissions() RETURNS trigger AS $check_del_permissions$
    BEGIN            
        IF OLD."default" = TRUE THEN
            RAISE EXCEPTION 'Khong the xoa quyen mac dinh';
        END IF;
        RETURN OLD;
    END;
$check_del_permissions$ LANGUAGE plpgsql;

CREATE TRIGGER check_del_permissions BEFORE DELETE ON permissions /*table name*/
    FOR EACH ROW EXECUTE PROCEDURE check_del_permissions();