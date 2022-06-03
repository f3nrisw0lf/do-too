<?php

function render_sub_todos($sub_todo_result) {
  echo "<ul class='list-group list-group-flush justify-content-center'>";
  while ($sub_todo = mysqli_fetch_assoc($sub_todo_result)) {
    $sub_todo_content = $sub_todo["content"];
    echo "<div>
            <li class='list-group-item border-0 p-0'>
              <input class='form-check-input' type='checkbox' id='checkboxNoLabel' value='' aria-label='...'>
              $sub_todo_content
            </li>
          </div>";
  }
  echo "</ul>";
}

function render_todo_modal($todo, $sub_todo_result) {
  $sub_todo_count = mysqli_num_rows($sub_todo_result);
  $todo_id = $todo["id"];
  $todo_content = $todo["content"];
  $todo_title = $todo["title"];

  echo "<!-- Button trigger modal -->";
  echo "<button type='button' class='btn btn-outline-light text-black border p-3 border-black rounded-1 d-flex justify-content-between align-items-center ' data-bs-toggle='modal' data-bs-target='#modal-$todo_id'>";
  echo "  <div class='text-start'> <h5 class='text-dark'> <strong> $todo_title </strong> </h5> <p>$todo_content</p> </div>";
  echo "  <span class='badge bg-primary'>$sub_todo_count</span>";
  echo "</button>";
  echo "<!-- Modal -->";
  echo "<div class='modal fade' id='modal-$todo_id' tabindex='-1' aria-labelledby='modal-$todo_id' aria-hidden='true'>";
  echo "  <div class='modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg'>";
  echo "    <div class='modal-content px-5 pt-4'>";
  echo "      <button type='button' class='btn-close align-self-end' data-bs-dismiss='modal' aria-label='Close'></button>";
  echo "      <div class='modal-body px-0 mb-2'>";
  echo "        <h3>$todo_title</h3>";
  if ($sub_todo_count) render_sub_todos($sub_todo_result);
  else echo "Does not have a sub-todo :)";
  echo "      </div>";
  echo "      <div class='modal-footer'>";
  echo "        <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Close</button>";
  echo "        <button type='button' class='btn btn-primary'>Save changes</button>";
  echo "      </div>";
  echo "    </div>";
  echo "  </div>";
  echo "</div>";
}

function render_todo_card($DB_CONNECTION, $todo_result) {
  echo "<ul class='list-group'>";
  while ($todo = mysqli_fetch_assoc($todo_result)) {
    $id = $todo["id"];
    $sub_todo_result = mysqli_query($DB_CONNECTION, "SELECT * FROM sub_todo WHERE todo_id = '$id'");

    render_todo_modal($todo, $sub_todo_result);
  }
  echo "</ul>";
}
?>

<div class="p-4 my-5 flex-grow-1">
  <div class="h-100 p-2">
    <?php
    $folder_id = isset($_GET["id"]) ? $_GET["id"] : "";

    if (isset($_GET["id"])) {
      $query_folder = mysqli_query($DB_CONNECTION, "select * from folder where id = $folder_id limit 1");
      while ($folder = mysqli_fetch_assoc($query_folder)) {
        $folder_name = $folder["name"];
        echo "<h2> $folder_name </h2>";
      }
    } else {
      echo "<h2> All </h2>";
    }
    ?>

    <div class="pe-2 todo-list">
      <?php
      if ($folder_id) {
        $query_all_todos = mysqli_query($DB_CONNECTION, "SELECT * FROM todo WHERE id = 1 AND folder_id = $folder_id");

        render_todo_card($DB_CONNECTION, $query_all_todos);
      } else {
        $query_all_todos = mysqli_query($DB_CONNECTION, "SELECT * FROM todo WHERE id = 1");

        render_todo_card($DB_CONNECTION, $query_all_todos);
      }
      // $todo_without_folder = mysqli_query($DB_CONNECTION, "SELECT * FROM todo WHERE folder_id IS NULL");

      // render_todo_card($DB_CONNECTION, $todo_without_folder);
      ?>
    </div>
  </div>

  <?php
  include_once('src/components/AddTodo.php')
  ?>
</div>

<style>
  .todo-list {
    overflow-y: auto;
    height: 90%;
  }
</style>
