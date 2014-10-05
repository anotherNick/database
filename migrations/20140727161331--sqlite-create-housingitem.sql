CREATE TABLE "housingitem" (
    "id" INTEGER PRIMARY KEY,
    "name" TEXT,
    "can_trade" INTEGER,
    "can_auction" INTEGER,
    "housingtype_id" INTEGER,
    FOREIGN KEY ("housingtype_id")
        REFERENCES "housingtype" ("id")
        ON DELETE SET NULL
        ON UPDATE SET NULL
);
CREATE INDEX index_foreignkey_housingitem_housingtype ON "housingitem" (housingtype_id);
