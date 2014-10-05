CREATE TABLE "world" (
    "id" INTEGER PRIMARY KEY,
    "name" TEXT
);

ALTER TABLE "area" ADD "world_id" INTEGER;
DROP TABLE IF EXISTS tmp_backup;
CREATE TEMPORARY TABLE tmp_backup(
    "id",
    "name",
    "image",
    "world_id"
);
INSERT INTO tmp_backup SELECT * FROM "area";
DROP TABLE "area";
CREATE TABLE "area" (
    "id" INTEGER PRIMARY KEY,
    "name" TEXT,
    "image" TEXT,
    "world_id" INTEGER,
    FOREIGN KEY("world_id")
        REFERENCES "world"("id")
        ON DELETE SET NULL
        ON UPDATE SET NULL
);
CREATE INDEX index_foreignkey_area_world ON "area" ("world_id");
INSERT INTO "area" SELECT * FROM tmp_backup;
DROP TABLE tmp_backup;

CREATE TEMPORARY TABLE tmp_backup(
    "id",
    "votes_up",
    "votes_down",
    "area_id",
    "reagent_id"
);
INSERT INTO tmp_backup SELECT * FROM "areareagent";
DROP TABLE "areareagent";
CREATE TABLE "areareagent" 
    ( "id" INTEGER PRIMARY KEY,
    "votes_up" INTEGER,
    "votes_down" INTEGER,
    "area_id" INTEGER,
    "reagent_id" INTEGER,
    FOREIGN KEY("area_id")
        REFERENCES "area"("id")
        ON DELETE SET NULL
        ON UPDATE SET NULL,
    FOREIGN KEY("reagent_id")
        REFERENCES "reagent"("id")
        ON DELETE SET NULL 
        ON UPDATE SET NULL
);
CREATE INDEX index_foreignkey_areareagent_area ON "areareagent" ("area_id");
CREATE INDEX index_foreignkey_areareagent_reagent ON "areareagent" ("reagent_id");
INSERT INTO "areareagent" SELECT * FROM tmp_backup;
DROP TABLE tmp_backup;
