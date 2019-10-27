<?php
function adminer_object() {
	// required to run any plugin
	include_once "Plugins/plugin.php";


	// autoloader
	foreach (glob("Plugins/*.php") as $filename) {
		include_once "./$filename";
	}

	$plugins = array(
		/*
		  Parametry pluginu
		  1. typ databáze - pro použití MySQL zadejte server
		  2. server - defaultně localhost
		  3. login - přihlašovací jméno
		  4. heslo - VYPLŇTE POUZE POKUD JSTE NA LOCALHOSTU
		  5. název DB - název databáze do které se adminer přepne
		*/
		//new FillLoginForm("server","wm138.wedos.net","a153246_eshop","A9S7G5bn","d153246_eshop")
	);

	/* It is possible to combine customization and plugins:
	class AdminerCustomization extends AdminerPlugin {
	}
	return new AdminerCustomization($plugins);
	*/

	return new AdminerPlugin($plugins);
}

// include original Adminer or Adminer Editor
include "./adminer.php";
?>