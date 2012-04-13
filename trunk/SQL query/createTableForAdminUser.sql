-- Table: permissions

-- DROP TABLE permissions;

CREATE TABLE permissions
(
  id serial NOT NULL,
  permkey character varying(30) NOT NULL,
  permname character varying(30) NOT NULL,
  "default" boolean DEFAULT false,
  CONSTRAINT permissions_pkey PRIMARY KEY (id),
  CONSTRAINT permissions_permkey_key UNIQUE (permkey)
)
WITH (OIDS=FALSE);
ALTER TABLE permissions OWNER TO postgres;

-- Table: roles

-- DROP TABLE roles;

CREATE TABLE roles
(
  id serial NOT NULL,
  rolename character varying(30) NOT NULL,
  CONSTRAINT roles_pkey PRIMARY KEY (id),
  CONSTRAINT roles_rolename_key UNIQUE (rolename)
)
WITH (OIDS=FALSE);
ALTER TABLE roles OWNER TO postgres;

-- Table: role_perms

-- DROP TABLE role_perms;

CREATE TABLE role_perms
(
  id serial NOT NULL,
  roleid integer NOT NULL,
  permid integer NOT NULL,
  "value" boolean NOT NULL DEFAULT false,
  adddate timestamp without time zone NOT NULL,
  CONSTRAINT role_perms_pkey PRIMARY KEY (id),
  CONSTRAINT role_perms_permid_fkey FOREIGN KEY (permid)
      REFERENCES permissions (id) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE CASCADE,
  CONSTRAINT role_perms_roleid_fkey FOREIGN KEY (roleid)
      REFERENCES roles (id) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE CASCADE,
  CONSTRAINT role_perms_roleid_key UNIQUE (roleid, permid)
)
WITH (OIDS=FALSE);
ALTER TABLE role_perms OWNER TO postgres;

-- Table: users

-- DROP TABLE users;

CREATE TABLE users
(
  id serial NOT NULL,
  username character varying(20) NOT NULL,
  "password" character varying(32) NOT NULL,
  "default" boolean DEFAULT false,
  CONSTRAINT users_pkey PRIMARY KEY (id),
  CONSTRAINT users_username_key UNIQUE (username)
)
WITH (OIDS=FALSE);
ALTER TABLE users OWNER TO postgres;

-- Table: user_perms

-- DROP TABLE user_perms;

CREATE TABLE user_perms
(
  id serial NOT NULL,
  userid integer NOT NULL,
  permid integer NOT NULL,
  "value" boolean NOT NULL DEFAULT false,
  adddate timestamp without time zone NOT NULL,
  CONSTRAINT user_perms_pkey PRIMARY KEY (id),
  CONSTRAINT user_perms_permid_fkey FOREIGN KEY (permid)
      REFERENCES permissions (id) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE CASCADE,
  CONSTRAINT user_perms_userid_fkey FOREIGN KEY (userid)
      REFERENCES users (id) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE CASCADE,
  CONSTRAINT user_perms_userid_key UNIQUE (userid, permid)
)
WITH (OIDS=FALSE);
ALTER TABLE user_perms OWNER TO postgres;

-- Table: user_roles

-- DROP TABLE user_roles;

CREATE TABLE user_roles
(
  userid integer NOT NULL,
  roleid integer NOT NULL,
  adddate timestamp without time zone NOT NULL,
  CONSTRAINT user_roles_roleid_fkey FOREIGN KEY (roleid)
      REFERENCES roles (id) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE CASCADE,
  CONSTRAINT user_roles_userid_fkey FOREIGN KEY (userid)
      REFERENCES users (id) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE CASCADE,
  CONSTRAINT user_roles_userid_key UNIQUE (userid, roleid)
)
WITH (OIDS=FALSE);
ALTER TABLE user_roles OWNER TO postgres;