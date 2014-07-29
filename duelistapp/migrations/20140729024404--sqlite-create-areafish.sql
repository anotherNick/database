CREATE TABLE "areafish" (
    "id" INTEGER PRIMARY KEY,
    "votes_up" INTEGER,
    "votes_down" INTEGER,
    "area_id" INTEGER,
    "fish_id" INTEGER,
    FOREIGN KEY("area_id")
        REFERENCES "area"("id")
        ON DELETE SET NULL
        ON UPDATE SET NULL,
    FOREIGN KEY("fish_id")
        REFERENCES "fish"("id")
        ON DELETE SET NULL
        ON UPDATE SET NULL
);

CREATE INDEX index_foreignkey_areafish_fish ON "areafish" (fish_id) ;
CREATE INDEX index_foreignkey_areafish_area ON "areafish" (area_id) ;
