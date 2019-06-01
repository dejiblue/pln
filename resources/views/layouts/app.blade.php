<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>
    <script>
        
        window.urlapp = "{{ env('APP_URL') }}";
        document.csrf_token = "{{csrf_token()}}" ; 
        document.trans = JSON.parse("{{ json_encode(array_merge(trans('forgot'),trans('app'),trans('login'),trans('register'))) }}".replace(/&quot;/g,'"'));  ; 

        window.htmlentities = {

            encode : function(str) {
                var buf = [];
                
                for (var i=str.length-1;i>=0;i--) {
                    buf.unshift(['&#', str[i].charCodeAt(), ';'].join(''));
                }
                
                return buf.join('');
            },

            decode : function(str) {
                return str.replace(/&#(\d+);/g, function(match, dec) {
                    return String.fromCharCode(dec);
                });
            }

        };
        console.log( document.trans );
    </script>
</head>
<body>
    @yield('content')
</body>

<style>

    body, html {
        width: 100%;
        height: 100%;
        padding: 0;margin: 0;
        overflow: hidden;
        position: relative;      
    }
    /*
    * Stylisation du loader 
    */
    .loader-quart {
        border-radius: 50px;
        border: 6px solid rgba(255, 255, 255, 0.4);
    }

    .loader {
        width: 50px;
        height: 50px;
        display: inline-block;
        vertical-align: middle;
        position: relative;
        top : 50%;
        left : 50% ; 
        transform: translate( -50% , -50% );
        background: none !important;
    }

    .loader-quart:after {
        content: '';
        position: absolute;
        top: -6px;
        left: -6px;
        bottom: -6px;
        right: -6px;
        border-radius: 50px;
        border: 6px solid transparent;
        border-top-color: #FFF;
        -webkit-animation: spin 1s linear infinite;
        -moz-animation: spin 1s linear infinite;
        animation: spin 1s linear infinite;
    }

    .content-loader{
        position: absolute;
        top: 0;left: 0;right: 0;bottom: 0;
        background-color: rgba(62, 193, 211, 0.8);
        z-index: 3000;
    }
  
    /* Animations */
      @-webkit-keyframes spin {
        from {
          -webkit-transform: rotate(0deg); }

        to {
          -webkit-transform: rotate(360deg); } }
      @-moz-keyframes spin {
        from {
          -moz-transform: rotate(0deg); }

        to {
          -moz-transform: rotate(360deg); } }
      @keyframes spin {
        from {
          -webkit-transform: rotate(0deg);
          -moz-transform: rotate(0deg);
          -ms-transform: rotate(0deg);
          -o-transform: rotate(0deg);
          transform: rotate(0deg); }

        to {
          -webkit-transform: rotate(360deg);
          -moz-transform: rotate(360deg);
          -ms-transform: rotate(360deg);
          -o-transform: rotate(360deg);
          transform: rotate(360deg); } }

        /*
        *   Stylisation du color de formulaire 
        */

      
        .cont-app{
            height: 100%;
            width: 100%;
            overflow: auto;
            background-color: rgba(62, 193, 211, 0.8);
        }

        .col-form{
            margin: 3rem 0rem;
        }

        .col-form-cont{
          background-color: #FFF;
          padding: 4rem 2rem;
          border-radius: 0.2rem;
          box-shadow: 0px 0px 10px rgba(0,0,0,0.1) ; 
        }


        /*
        *   stylisation de tout les Inputs de l'applications 
        */ 

        .form-login{

            margin-top: 2rem;

        }

        .cont-app .form-control {
            color: #495057;
            background-color: #EBEBEB;
            border: 1px solid rgba(0,0,0,0);
        }

        .cont-app .form-control.with{
            background-color: #FFFFFF ; 
        }

        .form-control:focus {
            color: #495057;
            background-color: #fff;
            border-color: #80bdff;
            outline: 0;
            box-shadow: 0 0 0 0.2rem rgba(62, 193, 211, 0.25);
        }

        .form-control.is-invalid, .was-validated .custom-select:invalid, .custom-select.is-invalid {
            border-color: #dc3545;
            box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
        }

        /* Stylisation des Icons de l'Input */

        .cont-app .input-icon{
      
            position: absolute;
            z-index: 300;
            top: 0.4rem;
            left: 0.75rem;
      
        }
        
        .cont-app .input-loader {
            position: absolute;
            z-index: 300;
            top: 1.2rem;
            left: 2rem;
        }

        .loader-input .loader{
            width: 25px;
            height: 25px;
        }

        .cont-app .input-icon-txt{
          
            padding-left: 2.5rem;
            border-radius: 0.25rem !important; 

        }

      
        /*
        *   Stylisation des button primary
        */

        .cont-app .btn-primary{
            background-color: #3EC1D3;
            border-color: #2ba1b1;
        }

        .cont-app .btn-primary:hover {
            color: #fff;
            background-color: #31b2c3;
            border-color: #2ba1b1;
        }

        .cont-app .btn-primary:not(:disabled):not(.disabled):active, .btn-primary:not(:disabled):not(.disabled).active, .show > .btn-primary.dropdown-toggle{
            background-color: #2b9faf;
            border-color: #2ba1b1;
        }

        .cont-app .page-item.active .page-link{
            background-color: #3EC1D3;
            border-color: #2ba1b1;
        }

        .cont-app .btn-link{
            color: #3EC1D3;
        }

        .cont-app .btn-link:hover{
            color: #2b9faf;
        }


        /* écrasé les elements */
        a {
            color: #333333;
        }

        /*popover*/
        .popover{
            max-width: 340px !important;
        }
        
</style>
</html>
