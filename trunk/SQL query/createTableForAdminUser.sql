-- Table: permissions

-- DROP TABLE permissions;

CREATE TABLE permissions
(
  ID serial NOT NULL,
  permKey character varying(30) NOT NULL,
  permName character varying(30) NOT NULL,
  CONSTRAINT permissions_pkey PRIMARY KEY (ID),
  CONSTRAINT permissions_permKey_key UNIQUE (permKey)
)
WITH (OIDS=FALSE);
ALTER TABLE permissions OWNER TO postgres;

-- Table: roles

-- DROP TABLE roles;

CREATE TABLE roles
(
  ID serial NOT NULL,
  roleName character varying(30) NOT NULL,
  CONSTRAINT roles_pkey PRIMARY KEY (ID),
  CONSTRAINT roles_roleName_key UNIQUE (roleName)
)
WITH (OIDS=FALSE);
ALTER TABLE roles OWNER TO postgres;

-- Table: role_perms

-- DROP TABLE role_perms;

CREATE TABLE role_perms
(
  ID serial NOT NULL,
  roleID integer NOT NULL,
  permID integer NOT NULL,
  value boolean NOT NULL DEFAULT false,
  addDate timestamp without time zone NOT NULL,
  CONSTRAINT role_perms_pkey PRIMARY KEY (ID),
  CONSTRAINT role_perms_roleID_key UNIQUE (roleID, permID)
)
WITH (OIDS=FALSE);
ALTER TABLE role_perms OWNER TO postgres;

-- Table: users

-- DROP TABLE users;

CREATE TABLE users
(
  ID serial NOT NULL,
  username character varying(20) NOT NULL,
  password character varying(20) NOT NULL,
  CONSTRAINT users_pkey PRIMARY KEY (ID),
  CONSTRAINT users_username_key UNIQUE (username)
)
WITH (OIDS=FALSE);
ALTER TABLE users OWNER TO postgres;

-- Table: user_perms

-- DROP TABLE user_perms;

CREATE TABLE user_perms
(
  ID serial NOT NULL,
  userID integer NOT NULL,
  permID integer NOT NULL,
  value boolean NOT NULL DEFAULT false,
  addDate timestamp without time zone NOT NULL,
  CONSTRAINT user_perms_pkey PRIMARY KEY (ID),
  CONSTRAINT user_perms_userID_key UNIQUE (userID, permID)
)
WITH (OIDS=FALSE);
ALTER TABLE user_perms OWNER TO postgres;

-- Table: user_roles

-- DROP TABLE user_roles;

CREATE TABLE user_roles
(
  userID integer NOT NULL,
  roleID integer NOT NULL,
  addDate timestamp without time zone NOT NULL,
  CONSTRAINT user_roles_userID_key UNIQUE (userID, roleID)
)
WITH (OIDS=FALSE);
ALTER TABLE user_roles OWNER TO postgres;