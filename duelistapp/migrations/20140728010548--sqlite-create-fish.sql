CREATE TABLE "fish" (
    "id" INTEGER PRIMARY KEY,
    "name" TEXT,
    "image" TEXT,
    "rank" INTEGER,
    "description" TEXT,
    "initial_xp" INTEGER,
    "rarity_id" INTEGER,
    "class_id" INTEGER,
    "aquarium_id" INTEGER,
    FOREIGN KEY("rarity_id")
        REFERENCES "rarity"("id")
        ON DELETE SET NULL
        ON UPDATE SET NULL,
    FOREIGN KEY("class_id")
        REFERENCES "class"("id")
        ON DELETE SET NULL ON UPDATE SET NULL,
    FOREIGN KEY("aquarium_id")
        REFERENCES "housingitem"("id")
        ON DELETE SET NULL
        ON UPDATE SET NULL
);

CREATE INDEX index_foreignkey_fish_housingitem ON "fish" (aquarium_id) ;
CREATE INDEX index_foreignkey_fish_class ON "fish" (class_id) ;
CREATE INDEX index_foreignkey_fish_rarity ON "fish" (rarity_id) ;
