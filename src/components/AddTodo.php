<?php
$todo = filter_input(INPUT_POST, 'todo', FILTER_SANITIZE_ADD_SLASHES);
$folder_id = isset($_GET["id"]) ? filter_input(INPUT_GET, 'id', FILTER_SANITIZE_ADD_SLASHES) : NULL;
$current_date = date("Y/m/d");

if ($todo) {
  if ($folder_id)
    mysqli_query($DB_CONNECTION, "INSERT INTO todo(user_id, folder_id, content, creation_date) VALUES (1, $folder_id, '$todo', '$current_date')");
  else
    mysqli_query($DB_CONNECTION, "INSERT INTO todo(user_id, content, creation_date) VALUES (1, '$todo', '$current_date')");
}
?>

<form action="" method="post" class="mt-3 p-1">
  <div class="input-group">
    <span class="input-group-text" name="todo" id="todo">Add Todo</span>
    <input type="text" class="form-control" name="todo" aria-label="todo" placeholder="Add a Task" aria-describedby="inputGroup-sizing-default">
    <button type="submit" class="d-none"></button>
  </div>
</form>
