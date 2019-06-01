

var request = require("request");

var options = { method: 'POST',
  url: 'https://api.uptimerobot.com/v2/getAccountDetails',
  headers:
   { 'content-type': 'application/x-www-form-urlencoded',
     'cache-control': 'no-cache' },
  form: { api_key: 'u676118-b4e478eeb833aae1b957d78a', format: 'json' } };
 
request(options, function (error, response, body) {
  if (error) throw new Error(error);
 
  console.log(body);
});