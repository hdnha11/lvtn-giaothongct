-- Ham thuc hien cap nhat neu ko ton tai insert vao bang user_roles
-- Cung co the la nguoc lai chen vao neu trung se update gia tri cu
CREATE OR REPLACE FUNCTION replace_into_user_roles(uid INT, rid INT, adate TIMESTAMP) RETURNS VOID AS
$$
BEGIN
    LOOP
        -- first try to update the key
        UPDATE user_roles SET adddate = adate WHERE userid = uid AND roleid = rid;
        IF found THEN
            RETURN;
        END IF;
        -- not there, so try to insert the key
        -- if someone else inserts the same key concurrently,
        -- we could get a unique-key failure
        BEGIN
            INSERT INTO user_roles(userid, roleid, adddate) VALUES (uid, rid, adate);
            RETURN;
        EXCEPTION WHEN unique_violation THEN
            -- Do nothing, and loop to try the UPDATE again.
        END;
    END LOOP;
END;
$$
LANGUAGE plpgsql;

-- user_perms
CREATE OR REPLACE FUNCTION replace_into_user_perms(uid INT, pid INT, val BOOLEAN, adate TIMESTAMP) RETURNS VOID AS
$$
BEGIN
    LOOP
        -- first try to update the key
        UPDATE user_perms SET "value" = val, adddate = adate WHERE userid = uid AND permid = pid;
        IF found THEN
            RETURN;
        END IF;
        -- not there, so try to insert the key
        -- if someone else inserts the same key concurrently,
        -- we could get a unique-key failure
        BEGIN
            INSERT INTO user_perms(userid, permid, "value", adddate) VALUES (uid, pid, val, adate);
            RETURN;
        EXCEPTION WHEN unique_violation THEN
            -- Do nothing, and loop to try the UPDATE again.
        END;
    END LOOP;
END;
$$
LANGUAGE plpgsql;

-- roles
CREATE OR REPLACE FUNCTION replace_into_roles(rid INT, rname TEXT) RETURNS VOID AS
$$
BEGIN
    LOOP
        -- first try to update the key
        UPDATE roles SET rolename = rname WHERE id = rid;
        IF found THEN
            RETURN;
        END IF;
        -- not there, so try to insert the key
        -- if someone else inserts the same key concurrently,
        -- we could get a unique-key failure
        BEGIN
            INSERT INTO roles(id, rolename) VALUES (rid, rname);
            RETURN;
        EXCEPTION WHEN unique_violation THEN
            -- Do nothing, and loop to try the UPDATE again.
        END;
    END LOOP;
END;
$$
LANGUAGE plpgsql;

-- role_perms
CREATE OR REPLACE FUNCTION replace_into_role_perms(rid INT, pid INT, val BOOLEAN, adate TIMESTAMP) RETURNS VOID AS
$$
BEGIN
    LOOP
        -- first try to update the key
        UPDATE role_perms SET "value" = val, adddate = adate WHERE roleid = rid AND permid = pid;
        IF found THEN
            RETURN;
        END IF;
        -- not there, so try to insert the key
        -- if someone else inserts the same key concurrently,
        -- we could get a unique-key failure
        BEGIN
            INSERT INTO role_perms(roleid, permid, "value", adddate) VALUES (rid, pid, val, adate);
            RETURN;
        EXCEPTION WHEN unique_violation THEN
            -- Do nothing, and loop to try the UPDATE again.
        END;
    END LOOP;
END;
$$
LANGUAGE plpgsql;

-- permissions
CREATE OR REPLACE FUNCTION replace_into_permissions(pid INT, pkey TEXT, pname TEXT) RETURNS VOID AS
$$
BEGIN
    LOOP
        -- first try to update the key
        UPDATE permissions SET permkey = pkey, permname = pname WHERE id = pid OR permkey = pkey;
        IF found THEN
            RETURN;
        END IF;
        -- not there, so try to insert the key
        -- if someone else inserts the same key concurrently,
        -- we could get a unique-key failure
        BEGIN
            INSERT INTO permissions(permkey, permname) VALUES (pkey, pname);
            RETURN;
        EXCEPTION WHEN unique_violation THEN
            -- Do nothing, and loop to try the UPDATE again.
        END;
    END LOOP;
END;
$$
LANGUAGE plpgsql;

-- users
CREATE OR REPLACE FUNCTION replace_into_users(uid INT, uname TEXT, pass TEXT) RETURNS VOID AS
$$
BEGIN
    LOOP
        -- first try to update the key
        UPDATE users SET username = uname, "password" = pass WHERE id = uid OR username = uname;
        IF found THEN
            RETURN;
        END IF;
        -- not there, so try to insert the key
        -- if someone else inserts the same key concurrently,
        -- we could get a unique-key failure
        BEGIN
            INSERT INTO users(username, "password") VALUES (uname, pass);
            RETURN;
        EXCEPTION WHEN unique_violation THEN
            -- Do nothing, and loop to try the UPDATE again.
        END;
    END LOOP;
END;
$$
LANGUAGE plpgsql;