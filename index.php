<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="./bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.3/font/bootstrap-icons.css">
  <title>Document</title>
</head>

<body class="">
  <?php
  include_once("./src/app.php");
  ?>

  <script src="./bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

<style>
  body {
    padding: 0;
    position: absolute;
    top: 0;
    bottom: 0;
    right: 0;
    left: 0;
  }

  button,
  button:focus {
    outline: none;
    background: transparent;
    border: 1px solid transparent;
  }

  button:active {
    outline: none;
    background: transparent;
    border: 1px solid grey;
  }

  /* Works on Firefox */
  * {
    scrollbar-width: thin;
  }

  /* Works on Chrome, Edge, and Safari */
  *::-webkit-scrollbar {
    width: 8px;
    border-radius: 10px;
  }

  html::-webkit-scrollbar-track {
    background: rgb(179, 177, 177);
    border-radius: 10px;
  }

  html::-webkit-scrollbar-thumb {
    background: rgb(136, 136, 136);
    border-radius: 10px;
  }

  *::-webkit-scrollbar-thumb:hover {
    background: rgb(100, 100, 100);
    border-radius: 10px;
  }

  *::-webkit-scrollbar-thumb:active {
    background: rgb(68, 68, 68);
    border-radius: 10px;
  }
</style>

</html>
