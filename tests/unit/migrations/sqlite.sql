/**
 * SQLite
 */

DROP TABLE IF EXISTS "post";
CREATE TABLE "post" (
  "id"  INTEGER NOT NULL PRIMARY KEY,
  "title" TEXT NOT NULL,
  "body" TEXT NOT NULL
);

DROP TABLE IF EXISTS "audit_data";
CREATE TABLE "audit_data" (
  "id"  INTEGER NOT NULL PRIMARY KEY,
  "audit_id"  INTEGER(11) NOT NULL,
  "name"  TEXT(255) NOT NULL,
  "type"  TEXT(255),
  "packed"  INTEGER(1) NOT NULL,
  "data"  BLOB NOT NULL
);

DROP TABLE IF EXISTS "audit_entry";
CREATE TABLE "audit_entry" (
  "id"  INTEGER NOT NULL PRIMARY KEY,
  "created"  TEXT NOT NULL,
  "start_time"  REAL,
  "end_time"  REAL,
  "duration"  REAL,
  "user_id"  INTEGER(11),
  "ip"  TEXT(45),
  "referrer"  TEXT(512),
  "origin"  TEXT(512),
  "redirect"  TEXT(255),
  "url"  TEXT(512),
  "route"  TEXT(255),
  "data"  BLOB,
  "memory"  INTEGER(11),
  "memory_max"  INTEGER(11),
  "request_method"  TEXT(255)
);

DROP TABLE IF EXISTS "audit_error";
CREATE TABLE "audit_error" (
  "id"  INTEGER NOT NULL PRIMARY KEY,
  "audit_id"  INTEGER(11) NOT NULL,
  "created"  TEXT NOT NULL,
  "message"  TEXT(512) NOT NULL,
  "code"  INTEGER(11),
  "file"  TEXT(512),
  "line"  INTEGER(11),
  "trace"  BLOB,
  "emailed"  INTEGER(11) NOT NULL
);

DROP TABLE IF EXISTS "audit_javascript";
CREATE TABLE "audit_javascript" (
  "id"  INTEGER NOT NULL PRIMARY KEY,
  "audit_id"  INTEGER(11) NOT NULL,
  "created"  TEXT NOT NULL,
  "type"  TEXT(20) NOT NULL,
  "message"  TEXT NOT NULL,
  "origin"  TEXT(512),
  "data"  BLOB
);

DROP TABLE IF EXISTS "audit_trail";
CREATE TABLE "audit_trail" (
  "id"  INTEGER NOT NULL PRIMARY KEY,
  "audit_id"  INTEGER(11),
  "user_id"  INTEGER(11),
  "old_value"  TEXT,
  "new_value"  TEXT,
  "action"  TEXT(255) NOT NULL,
  "model"  TEXT(255) NOT NULL,
  "field"  TEXT(255),
  "stamp"  TEXT NOT NULL,
  "model_id"  TEXT(255) NOT NULL
);
