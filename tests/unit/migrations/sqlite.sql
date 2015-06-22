/**
 * SQLite
 */

DROP TABLE IF EXISTS "post";

CREATE TABLE "post" (
  "id" INTEGER NOT NULL PRIMARY KEY,
  "title" TEXT NOT NULL,
  "body" TEXT NOT NULL
);
