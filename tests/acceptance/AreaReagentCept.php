<?php 
$I = new AcceptanceTester($scenario);
$I->wantTo('Add an Area to a Reagent');
$I->amOnPage('/reagents/Acorn');
$I->see('Wizard101 Acorn Reagent');
$I->click(['id' => 'areas-add-link']);
// Alternative: $I->click('//*[@id="areas-add-link"]');
$I->see('or Cancel');
$I->waitForJS("return jQuery.active == 0;", 60);
$I->click(".//*[@id='s2id_areas-add-select']/a");
$I->pressKey('#select2-drop input.select2-input', 'stone', WebDriverKeys::ENTER);
$I->see('Stone Town', '.select2-chosen' );
$I->click('Create');
$I->see('Stone Town');
