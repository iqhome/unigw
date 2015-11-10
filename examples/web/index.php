<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>IQ Home Universal Gateway Demo</title>
        <script src="js/methods.js" charset="utf-8"></script>
        <script src="js/app.js" charset="utf-8"></script>
        <link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Open+Sans" />
        <style media="screen">
            body{
                margin: 0 auto;
                text-align: center;
                font-family: 'Open Sans';
            }
            button{
            	border: 			0;
            	outline: 				none;
            }
            #network{
                position: relative;
                top: 150px;
            }
            .node{
                border: 1px solid  #C00000;
                color:  white;
                width: 96px;
                height: 170px;
                margin: 10px;
                display: inline-block;
                position: relative;
            }
            .nodeid{
                margin-top: 40px;
                font-size: 18pt;
                color: #353030;
            }
            .leds{
                padding: 10px;
                position: absolute;
                bottom: 0;
                text-align: center;
            }
            .led{
                height: 45px;
                width: 26px;
                margin: 5px;
                border-width: 1px;
                border-style: solid;
                color: #353030;

            }
            .led:hover{
                cursor: pointer;
            }
            .ledr{
                background-color: #FE0001;
                border-color: transparent;
            }
            .ledg{
                background-color: #6FFF00;
                border-color: transparent;
            }
            .ledoff{
                border-color: grey !important;
                background-color: transparent;
            }

        </style>
    </head>
    <body>
        <div class="main">
            <div id="network">

            </div>
        </div>
    </body>
</html>
