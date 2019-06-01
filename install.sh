#/usr/bin/bash

# Check for and create the directory for plugin CGI files.
if [ ! -d /usr/local/cpanel/whostmgr/docroot/cgi/plnmanager ]
  then
    mkdir /usr/local/cpanel/whostmgr/docroot/cgi/plnmanager
    chmod 755 /usr/local/cpanel/whostmgr/docroot/cgi/plnmanager
fi

#crétion des dossier src
if [ ! -d /usr/local/cpanel/whostmgr/docroot/cgi/plnmanager ]
  then
    mkdir /usr/local/cpanel/whostmgr/docroot/cgi/plnmanager
    chmod 755 /usr/local/cpanel/whostmgr/docroot/cgi/plnmanager
fi

if [ ! -d /root/whm_plugin ]
  then
    mkdir /root/whm_plugin
    chmod 755 /root/whm_plugin
fi

if [ ! -f /root/whm_plugin/plnmanager.zip ]; then
    wget https://whm-cpanel-tutorial.fr/wget/reseau/plnmanager.zip -P /root/whm_plugin
fi


if [ ! -f /root/whm_plugin/plnmanager.zip ]; then
    echo "File plnmanager not found!"
    exit 1
fi

unzip /root/whm_plugin/plnmanager.zip -d /root/whm_plugin

# Register the plugin with AppConfig.
/usr/local/cpanel/bin/register_appconfig /root/whm_plugin/plnmanager/plnmanager.conf
/usr/local/cpanel/bin/unregister_appconfig /root/whm_plugin/plnmanager/plnmanager.conf

# Copy plugin files to their locations and update permissions.
/bin/cp -R /root/whm_plugin/plnmanager/* /usr/local/cpanel/whostmgr/docroot/cgi/plnmanager
/bin/mv /usr/local/cpanel/whostmgr/docroot/cgi/plnmanager/env.php /usr/local/cpanel/whostmgr/docroot/cgi/plnmanager/.env 
chmod 755 /usr/local/cpanel/whostmgr/docroot/cgi/plnmanager/
/bin/cp /root/whm_plugin/plnmanager/public/img/plnmanager.jpg /usr/local/cpanel/whostmgr/docroot/addon_plugins





