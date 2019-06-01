//Installation de WP-CLI lors de l'installation du serveur 
	//instalation des donner utile par le serveur ( ex : création d'un dossier PLN manager )
	//ceci fait qu'un serveur est disponible ou pas ( s'i en manque des composante )

//si ce commande est "wp --info" : -bash: wp: command not found 
- curl -O https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar
- chmod +x wp-cli.phar
- sudo mv wp-cli.phar /usr/local/bin/wp
- sudo wp --info

//Création de donner utile a pln manager
- mkdir /root/plnmanager
- cd /root/plnmanager



//Téléchargement et décompression de wordpress
- wp core download --allow-root --locale=fr_FR --force ('Success: WordPress downloaded')
- chown -R www-data:www-data ./

//Configuration de wordpress 
- wp config create --allow-root --dbhost=localhost --dbname=doma9242_ezyypuJHMPyy --dbuser=doma9242_zVKUhwK --dbpass=sFmgW605703kBX --locale=fr_FR  ('Success: Generated 'wp-config.php' file')
- chown -R doma9242:doma9242 ./wp-config.php

//Installation de wordpress
- wp core --allow-root install --url=domainefakertest.com --title="titre du blog" --admin_user=dsdsdsdsd --admin_email=ahheldion@gmail.com --admin_password=qsdqsdqsdqsdqsdqsd  ('Success: WordPress installed successfully')
- wp option update --allow-root blog_public 'false'  ('Success: Updated 'blog_public' option')


//Configuration wordpress 
- wp config --allow-root set WP_HOME domainefakertest.com --raw ('Success: Added the constant 'WP_HOME' to the 'wp-config.php' file with the raw value 'domainefakertest.com')
- wp config --allow-root set WP_SITEURL domainefakertest.com --raw ('Success: Added the constant 'WP_SITEURL' to the 'wp-config.php' file with the raw value 'domainefakertest.com')


//Ajoute de l'option linkwheel ( widget )
- wp widget add text --allow-root sidebar-1 2 --text="[googlebot]<a href='https:domainefakertest.com'>domainefakertest.com</a>[/googlebot]"  ('Success: Added widget to sidebar.')

//Installation de Thème et de plugin 
- wp theme install --allow-root magzee --activate


https://api.wordpress.org/themes/info/1.1/?action=theme_information&request[slug]=magzee


('Success: Switched to 'MagZee' theme.
Success: Installed 1 of 1 themes.')

//Installation de plugin et de théme par défaut 

//Installation de théme 

//création de post ( Article )

//copy de fichier dans le route de l'application 

////////////////////////
En cas d'erreur on a Error: *****************.
///////////////////////
"DB_HOST" => "localhost"
"DB_DATABASE" => "doma9242_ezyypuJHMPyy"
"DB_USERNAME" => "doma9242_zVKUhwK"
"DB_PASSWORD" => "sFmgW605703kBX"