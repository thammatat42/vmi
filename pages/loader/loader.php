<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <style>

        .loader {
            position: absolute;
            left: 50%;
            top: 50%;
            z-index: 1;
            width: 120px;
            height: 120px;
            margin: -76px 0 0 -76px;
            border: 16px solid #f3f3f3;
            border-radius: 50%;
            border-top: 16px solid #3498db;
            -webkit-animation: loader 1s ease-in-out infinite alternate;
            animation: loader 1s ease-in-out infinite alternate;
        }

        .loader:before {
        content: " ";
        position: absolute;
        z-index: -1;
        top: 5px;
        left: 5px;
        right: 5px;
        bottom: 5px;
        border: 3px solid #09acfd;
        }

        .loader:after {
        content: " ";
        position: absolute;
        z-index: -1;
        top: 15px;
        left: 15px;
        right: 15px;
        bottom: 15px;
        border: 3px solid #09acfd;
        }

        @keyframes loader {
        from {transform: rotate(0deg) scale(1,1);border-radius:0px;}
        to {transform: rotate(360deg) scale(0, 0);border-radius:50px;}
        }
        @-webkit-keyframes loader {
        from {-webkit-transform: rotate(0deg) scale(1, 1);border-radius:0px;}
        to {-webkit-transform: rotate(360deg) scale(0,0 );border-radius:50px;}
        }
    </style>
</head>
<body>
    <div class="loader"></div>
</body>
</html>