<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="./bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.3/font/bootstrap-icons.css">
  <title>DoToo</title>
</head>

<body class="">
  <?php
  session_start();
  ob_start();

  include_once("src/Route.php");

  Route::add('/', function () {
    include_once("src/db_connection.php");
    if (isset($_SESSION["user_id"])) {
      include_once("src/pages/Me.php");
    } else
      include_once("src/pages/Landing.php");
  });

  Route::add('/', function () {
    include_once("src/db_connection.php");
    if (isset($_SESSION["user_id"])) {
      include_once("src/pages/Me.php");
    } else
      include_once("src/pages/Landing.php");
  }, 'post');

  Route::add('/login', function () {
    include_once("src/pages/Login.php");
  });

  Route::add('/login', function () {
    include_once("src/pages/Login.php");
  }, 'post');

  Route::add('/signup', function () {
    include_once("src/pages/Signup.php");
  });

  Route::add('/signup', function () {
    include_once("src/pages/Signup.php");
  }, 'post');

  Route::add('/logout', function () {
    session_destroy();
    header("Location: /do-too");
  });

  Route::pathNotFound(function ($path) {
    echo "<div style='text-align: center; font-family: sans-serif; padding: 3rem;'>";
    echo "<div style='display: flex; justify-content: center; align-items: center; gap: 10px;'>";
    echo '<svg xmlns="http://www.w3.org/2000/svg" width="2.5rem" height="2.5rem" fill="currentColor" class="bi bi-code" viewBox="0 0 16 16">';
    echo '<path d="M5.854 4.854a.5.5 0 1 0-.708-.708l-3.5 3.5a.5.5 0 0 0 0 .708l3.5 3.5a.5.5 0 0 0 .708-.708L2.707 8l3.147-3.146zm4.292 0a.5.5 0 0 1 .708-.708l3.5 3.5a.5.5 0 0 1 0 .708l-3.5 3.5a.5.5 0 0 1-.708-.708L13.293 8l-3.147-3.146z"/>';
    echo '</svg>';
    echo "<h1 style='font-size: 3.052rem; margin: 0px;'>404</h1>";
    echo '<svg xmlns="http://www.w3.org/2000/svg" width="2.5rem" height="2.5rem" fill="currentColor" class="bi bi-code" viewBox="0 0 16 16">';
    echo '<path d="M5.854 4.854a.5.5 0 1 0-.708-.708l-3.5 3.5a.5.5 0 0 0 0 .708l3.5 3.5a.5.5 0 0 0 .708-.708L2.707 8l3.147-3.146zm4.292 0a.5.5 0 0 1 .708-.708l3.5 3.5a.5.5 0 0 1 0 .708l-3.5 3.5a.5.5 0 0 1-.708-.708L13.293 8l-3.147-3.146z"/>';
    echo '</svg>';
    echo "</div>";
    echo "<p style='color: gray; font-size: 1.25rem;'>Path " . htmlentities($path) . " does not exist...</p>";
    echo "<a href='/do-too' style='text-decoration: none;'> Go to home... </a>";
    echo "</div>";
  });

  Route::run('/do-too');
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