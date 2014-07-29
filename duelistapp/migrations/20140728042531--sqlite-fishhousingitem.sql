CREATE TABLE "fish_housingitem" (
    "id" INTEGER PRIMARY KEY,
    "housingitem_id" INTEGER,
    "fish_id" INTEGER,
    FOREIGN KEY("housingitem_id")
        REFERENCES "housingitem"("id")
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    FOREIGN KEY("fish_id")
        REFERENCES "fish"("id")
        ON DELETE CASCADE
        ON UPDATE CASCADE
);
CREATE INDEX index_for_fish_housingitem_housingitem_id
    ON "fish_housingitem" (housingitem_id);
CREATE INDEX index_for_fish_housingitem_fish_id
    ON "fish_housingitem" (fish_id);
CREATE UNIQUE INDEX UQ_fish_housingitemhousingitem_id__fish_id
    ON "fish_housingitem" ("housingitem_id","fish_id");
