CREATE TABLE "areareagentspawn" ( 
	"id" INTEGER PRIMARY KEY,
	"x_loc" INTEGER,
	"y_loc" INTEGER,
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

CREATE INDEX index_foreignkey_areareagentspawn_reagent ON "areareagentspawn" (reagent_id);
CREATE INDEX index_foreignkey_areareagentspawn_area ON "areareagentspawn" (area_id);
