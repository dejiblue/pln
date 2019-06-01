https://stackoverflow.com/questions/28908935/how-to-add-live-reload-when-using-the-command-cordova-serve

You can use browser-sync directly, without a cordova plugin. Follow below steps

npm install -g browser-sync
Copy contents of platform/android/platform_www to www
Add following to config.xml: <allow-navigation href="http://<YOUR-IP>:3000" />
Set base-href (if angular project) to http://<YOUR-IP>:3000/index.html
Run browser-sync -w -server inside www
Update content security policy for executing JS and assets from YOUR-IP:3000. Also allow webscokets (ws) for browser-sync
Deploy app using cordova run android
Please note that you will have to redeploy the app every time you add a new plugin, and also update the www folder with new platform_www contents.

Every time you update the www, browser-sync will automatically notify your webview, and refresh the same. If you have to restart the app and connection with browser-sync is lost, connect device to computer and refresh the app using chrome device inspector, and browser-sync will be live again.