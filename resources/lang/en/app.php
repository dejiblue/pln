<?php

return [

    
    /*
	*	Lang gélérale de l'applications 
    */

    //menu de l'application
    'menu.profile' => 'Profile',
    'menu.seveur' => 'Server',
    'menu.installsite' => 'Site Installation',
    'menu.manager' => 'Site Manager',
    'menu.history' => 'History',
    'menu.logout' => 'Log out',

    //erreur sur un requette ajax ( Un serveur qui ne repond pas )
    'form.error' => 'An error occured during the action please watch',

    //Imaeg de selection d'avatar 
    'avatar.button' => 'Upload Image',
    'avatar.error_size' => 'File size too large (more than: 1MB)',

    /**********************************************
	*	 			Page profile 
    ********************************************/

    'profile.view.edit' => 'Edit profile',

    'profile.update.btn.cancel' => 'CANCEL',
    'profile.update.btn' => 'UPDATE',
    'profile.update.advency' => 'Advency edit',

    'profile.update.form.name' => 'Your Name',
    'profile.update.form.nameError' => 'Name error',
    
    'profile.update.form.forname' => 'Your Surname',
    'profile.update.form.fornameError' => 'Surname error',

    'profile.update.form.email' => 'Your email',
    'profile.update.form.emailError' => 'Email error',

    'profile.update.alert' => 'your profile is changing successfully',

    //Modale de modification avancer du profile 
    'modale.profile.title' => 'Update advency profile',
    'modale.profile.btn' => 'UPDATE',
    'modale.profile.btn.cancel' => 'CANCEL',

    'modale.profile.form.password' => 'New password :',
    'modale.profile.form.passwordPlace' => 'Password',
    'modale.profile.form.passwordError' => 'Error password',

    'modale.profile.form.password_confirmation' => 'Retype password :',
    'modale.profile.form.password_confirmationPlace' => 'Password',
    'modale.profile.form.password_confirmationError' => 'Error password',

    'modale.profile.form.oldpassword' => 'Old password :',
    'modale.profile.form.oldpasswordPlace' => 'Password',
    'modale.profile.form.oldpasswordError' => 'Error password',

    //
    'modale.profile.form.sh.title' => 'Script Run',

    'modale.profile.form.sh.name' => 'Name script',

    'modale.profile.form.sh.run' => 'Run script',

    //
    'modale.profile.form.api.title' => 'Profil API',

    'modale.profile.form.InternetbskeyPlace' => 'InternetBS API KEY',
    'modale.profile.form.InternetbskeyError' => 'Error InternetBS API KEY',

    'modale.profile.form.InternetbspassPlace' => 'InternetBS PassWord Account',
    'modale.profile.form.InternetbspassError' => 'Error InternetBS PassWord Account',

    'modale.profile.form.Internetbsactive' => 'activé InternetBS',

    //
    'modale.profile.form.cpwp.title' => 'Profile WordPress & cPanel',

    'modale.profile.form.wppassword' => 'WP password :',
    'modale.profile.form.wppasswordPlace' => 'WordPress password',
    'modale.profile.form.wppasswordError' => 'Error WP password',

    'modale.profile.form.wpusername' => 'WP username :',
    'modale.profile.form.wpusernamePlace' => 'WordPress username',
    'modale.profile.form.wpusernameError' => 'Error WordPress username',

    'modale.profile.form.cppassword' => 'cPanel password:  :',
    'modale.profile.form.cppasswordPlace' => 'cPanel password: ',
    'modale.profile.form.cppasswordError' => 'Error password',

    'modale.profile.form.cpemail' => 'CP email :',
    'modale.profile.form.cpemailPlace' => 'cPanel / WordPress email',
    'modale.profile.form.cpemailError' => 'Error cPanel / WordPress email',

    //Page profile mais édition de la categorie 
    'cat.title' => 'Category',
    'cat.info' => '{{total}} types',

    'cat.load.warning' => 'Could not load category list',

    //formulaire new categorie 
    'cat.form.title' => 'Add category',
    'cat.form.btn' => 'Add',
    'cat.form.btn.cancel' => 'CANCEL',

    'ca.form.name' => 'Category name',
    'ca.form.namePlace' => 'Name',
    'ca.form.nameError' => 'Error Name',

    'ca.form.couleur' => 'Color',
    'ca.new.alert' => 'New category added',

    //mise a jour de catégorie 
    'cat.form.update.btn' => 'Update category',
    'cat.form.update.btn.cancel' => 'CANCEL',
    'ca.update.alert' => 'Catégory update with success',

    //delete categorie confirmation 
    'cat.delete.conf' => 'You really want to delete the category {{name}}',
    'cat.delete.alert' => 'Category <strong>{{name}}</strong> was deleted with success',
    'cat.delete.alert.erreur' => 'An error occurred while deleting the category<strong>{{name}}</strong> please try again',

    //section affichage des fichier google 
    'google.title' => 'Root directory files uploads',
    'google.info' => '{{total}} files',

    //erreur upload fichier 
    'google.form.error' => 'Error uploading the file',
    'google.form.success' => 'Success of the File Upload',
    'google.load.warning' => 'Can not load files',
    'google.delete.conf' => 'You really want to delete the file {{name}}',
    'google.delete.alert' => 'Success of deleting the file {{name}}',
    'google.delete.alert.erreur' => 'Error deleting the file {{name}}',


    //
    //Page serveur 
    //

    //pTableaux de liste de serveur 
    'serv.table.search' => 'Search',

    'serv.table.name' => 'Name' , 
    'serv.table.url' => 'Host' , 
    'serv.table.username' => 'Username' , 
    'serv.table.password' => 'PassWord' , 
    'serv.table.port' => 'Port' , 
    'serv.table.status' => 'Status' , 
    'serv.table.action' => 'Actions' , 

    'serv.btn.newserveur' => 'Add new server' , 

    //Formulaire de création et de mise a jour de serveur 

    
    'serv.form.serveur.install' => ' Installing PLN manager on the current server  ',

    'serv.form.title' => 'Server registration',
    'serv.form.btn' => 'Add server ',
    'serv.form.btn.cancel' => 'cancel',

    'serv.form.btn.installpln' => 'Install PLN manager',

    'serv.form.name' => 'Server name' , 
    'serv.form.namePlace' => 'Server name' , 
    'serv.form.nameError' => 'Error Server name' , 

    'serv.form.url' => 'Host' , 
    'serv.form.urlPlace' => 'https://' , 
    'serv.form.urlError' => 'Error Server name' , 

    'serv.form.port' => 'Port serveur' , 
    'serv.form.portPlace' => 'Port serveur' , 
    'serv.form.portError' => 'Port serveur Erreur' , 

    'serv.form.username' => 'Login' , 
    'serv.form.usernamePlace' => 'root' , 
    'serv.form.usernameError' => 'Error login server' , 

    'serv.form.sshport' => 'SSH port' , 
    'serv.form.sshportPlace' => 'SSH port' , 
    'serv.form.sshportError' => 'Error SSH port' , 

    'serv.form.token' => 'Password' , 
    'serv.form.tokenPlace' => 'Root password' , 
    'serv.form.tokenError' => 'Error root password' , 

    'serveur.update.alert' => 'Update the server with success',
    'serveur.new.alert' => 'The server <strong>{{name}}</strong> was created with success',

    'serveur.new.alertwithpln' => 'The server <strong>{{name}}</strong> as created with success and pln manager is also install with success on this server',

    'serveur.install.alertwithpln' => 'PLN manager was successfully added to the server <strong>{{name}}</strong>',

    //update de serveur
    'serv.form.update.title' => 'Update server',
    'serv.form.update.btn' => 'Update',
    'serv.form.update.btn.cancel' => 'cancel',

    //filtre du tableaux 
    'serv.liste.empty' => 'No records to display',
    'serv.liste.empty.filter' => 'There are no records matching your request',

    //les différent erreurs de la création de serveur 
    'serv.plni.error.sshauth' => 'Server <strong>{{name}}</strong> was created but auth ssh error',

    'serv.plni.error.sshauth.update' => 'Unable to install pln manager in the server <strong>{{name}}</strong> auth ssh incorrect',

    'serv.plni.error' => 'Server <strong>{{name}}</strong> was successfully created but an error occurred while installing PLN manager. Code error : <strong>{{codeerr}}</strong>',

    'serv.plni.error.update' => 'Unable to install pln manager in the server <strong>{{name}}</strong> an error has occurred. Code error : <strong>{{codeerr}}</strong>',

    'serv.plni.error.form.sshaut' => 'Error on the server <strong>{{name}}</strong> by connecting to the port : <strong>{{sshport}}</strong>',

    //lange de la creation des site web
    'install.warning'     =>   'To make a site installation, you must have at least one <strong>server</strong> and a minimum of <strong>catégory</strong>' , 


    //
    //Page installation site  
    //
    'installsite.cpanel.title' => 'cPanel account',
    'installsite.wp.title' => 'WordPress setup',


    'installsite.loader.theme' => 'Install theme',
    'installsite.loader.plugin' => 'Install plugin(s)',
    'installsite.loader.post' => 'Création des article',

    'installsite.error.create' => 'Error on all sites installations' , 
    'installsite.success.create' => 'Success on all the site installations' , 
    'installsite.warning.create' => '{{success}} site was created and {{error}} we had errors' ,

    //@TODO:a revoire 
    'installsite.title.loader.etape1' => 'Création compte cpanel' , 
    'installsite.info.loader.etape1' => 'étape 1/2' , 
    'installsite.title.loader.etape2' => 'Installation de wordpress' , 
    'installsite.info.loader.etape2' => 'étape 2/2' , 

    //
    //Page manager 
    //
    'manager.bulkpost.error.nombre' => 'Number of post created is less than the number of sites',
    
    'manager.bulkpost.error.create' => 'No post was created',
    'manager.bulkpost.success.create' => 'All posts have been created',
    'manager.bulkpost.warning.create' => '{{success}} we created and {{error}} we had errors ',

    'manager.widget.error' => 'Error on widget creation',

    'manager.widget.error.create' => 'No widget was added' , 
    'manager.widget.success.create'   => 'all the widget was added' , 
    'manager.widget.warning.create'   => '{{success}} we have been adding and {{error}} we had errors' , 

    'manager.session.error'   => 'Can not connect to the site {{url}}' , 
    'manager.session.success'   => 'connection to the site {{url}} OK' , 

    //Tableaux de liste des sites web 
    'manager.table.search'   => 'URL search' ,
    'manager.table.categorie.all'   => 'ALL' ,

    'manager.table.select'   => 'Selelct All' ,
    'manager.table.url'   => 'Url' ,
    'manager.table.categorie'   => 'Category' ,
    'manager.table.login'   => 'Login' ,
    'manager.table.upload'   => 'Bulk Upload' ,
    'manager.table.publier'   => 'Published' ,
    'manager.table.edit'   => 'Edit' ,
    'manager.table.widget'   => 'Bulk Push' ,

    'manager.table.btn.deleteall'   => 'Deleted the selection' ,
    'manager.table.btn.delete'   => 'DELETE' ,
    'manager.table.btn.publishb'   => 'Bulk push' ,
    'manager.table.btn.publish'   => 'Publised' ,
    'manager.table.btn.upload'   => 'UPLOAD' ,

    'manager.table.btn.connection'   => 'CONNECTION' ,

    'manager.table.btn.widget.title'   => ' Widget' ,
    'manager.table.btn.widget.popover'   => ' HTML widget WordPress ' ,

    //Page d'installation de site web 

    'installsite.form.domaine'   => 'Domain : ' , 
    'installsite.form.domainePlace'   => 'exemple.com' , 
    'installsite.form.domaineError'   => '* The field must be valid domain' , 

    'installsite.form.IPLabel'   => 'IP : ' , 
    'installsite.form.packageLabel'   => 'Package : ' , 

    'installsite.form.serveur'   => 'Servers : ' , 
    'installsite.form.categorie'   => 'Category : ' , 

    'installsite.form.domaineIP'  => 'Domain & ip' , 
    'installsite.form.domaineIPPlace'  => 'exemple.com 192.168.1.2' , 
    'installsite.form.domaineIPError'  => '* The field must be of format: exemple.com 192.168.1.2' , 

    'installsite.form.password'  => 'Password : ' , 
    'installsite.form.passwordPlace'  => 'Password : ' , 
    'installsite.form.passwordError'  => '* Error Password' , 

    'installsite.form.passJauge'  => '* the form of the password must be greater than 82%' , 

    'installsite.form.passconf'     => 'Password confirmation : ' , 
    'installsite.form.passconfPlace'    =>  'retype password : ' , 
    'installsite.form.passconfError'    => '* Error password confirmation' ,

    'installsite.form.contactemail'     => 'Email : ' , 
    'installsite.form.contactemailPlace'    =>  'Email ' , 
    'installsite.form.contactemailError'    => '* Error Email ' ,

    'installsite.form.wplang'    => 'Language : ' ,

    'installsite.form.wp_pass'     => 'WordPress Password : ' , 
    'installsite.form.wp_passPlace'    =>  'WP Password ' , 
    'installsite.form.wp_passError'    => '* Error Password ' ,

    'installsite.form.wp_username'     => 'WordPress Username : ' , 
    'installsite.form.wp_usernamePlace'    =>  'WP Username ' , 
    'installsite.form.wp_usernameError'    => '* Error Username ' ,

    'installsite.form.theme_name'     => 'Theme name : ' , 
    'installsite.form.theme_namePlace'    =>  'Theme name ' , 
    'installsite.form.theme_nameError'    => '* Error Theme name ' ,

    'installsite.form.theme_file'     => 'Auto install theme ' , 
    'installsite.form.theme_filePlace'    =>  'Auto install theme' ,

    'installsite.form.plugin_file'     => 'Auto install plugins ' , 
    'installsite.form.plugin_filePlace'    =>  'Auto install plugins' ,

    'installsite.form.upload_file'     => 'Bulk create post ' , 
    'installsite.form.upload_filePlace'    =>  'Upload files ' ,

    'installsite.form.cpanelOption'         => 'cPanel Option' , 
    'installsite.form.wordpressOption'         => 'Wordpress Option' , 

    //dans les memes page d'installation pour les informations lors de la création de site web 

    'installsite.info.init'    =>  ' : Variable Initialization ...' ,

    //////
    // Page hystory 

    'history.warning'    =>  'a problem has occurred, please refresh' ,

    //nom du fichier a télécharger 
    'filename.download' => 'History_pour_pour_date_{{date}}',


]; 
