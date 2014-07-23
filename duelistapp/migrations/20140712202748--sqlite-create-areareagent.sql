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

