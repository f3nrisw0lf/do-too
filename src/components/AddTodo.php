<?php
$user_id = $_SESSION["user_id"];
$todo = filter_input(INPUT_POST, 'todo', FILTER_SANITIZE_ADD_SLASHES);
$folder_id = isset($_GET["id"]) ? filter_input(INPUT_GET, 'id', FILTER_SANITIZE_ADD_SLASHES) : NULL;
$current_date = date("Y/m/d");

if (isset($todo))
  $pdo->query(isset($folder_id) ? "INSERT INTO todo(user_id, folder_id, title, creation_date) VALUES ($user_id, $folder_id, '$todo', '$current_date')" :  "INSERT INTO todo(user_id, title, creation_date) VALUES ($user_id, '$todo', '$current_date')");
?>

<form action="" method="post" class="mt-3 p-1">
  <div class="input-group">
    <span class="input-group-text" name="todo" id="todo">Add Todo</span>
    <input type="text" class="form-control" name="todo" aria-label="todo" placeholder="Add a Task"
      aria-describedby="inputGroup-sizing-default">
    <button type="submit" class="d-none"></button>
  </div>
</form>