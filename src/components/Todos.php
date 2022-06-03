<?php

$delete_todo_id = filter_input(INPUT_GET, 'delete_id', FILTER_SANITIZE_NUMBER_INT);

if (isset($delete_todo_id)) {
  mysqli_query($DB_CONNECTION, "DELETE FROM todo WHERE id=$delete_todo_id");

  header("./");
}

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
  echo "<div class='d-flex todo-item rounded-2'>";
  echo '<div class="bg-transparent border rounded-start p-2 d-flex">';
  echo '<input class="form-check-input mt-0 align-self-center" type="checkbox" value="" aria-label="Checkbox for following text input">';
  echo '</div>';
  echo "<button type='button' class='w-100 btn-outline-transparent bg-transparent text-black border p-3 border-black border-left-0 d-flex justify-content-between align-items-center ' data-bs-toggle='modal' data-bs-target='#modal-$todo_id'>";
  echo "  <div class='text-start'> <h5 class='text-dark'> <strong> $todo_title </strong> </h5> <p>$todo_content</p> </div>";
  echo "  <span class='badge bg-secondary ms-2'>$sub_todo_count</span>";

  echo "</button>";

  // Delete Button
  echo "<a href='./?delete_id=$todo_id' class='p-2 border-1 border-left-0 rounded-end d-flex' id='delete-todo-button'>";
  echo '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="dark" class="bi bi-trash align-self-center" viewBox="0 0 16 16">';
  echo '<path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>';
  echo '<path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>';
  echo '</svg>';
  echo "</a>";

  echo "</div>";
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
  echo "<ul class='list-group d-flex gap-1 todo-list p-1'>";
  while ($todo = mysqli_fetch_assoc($todo_result)) {
    $id = $todo["id"];
    $sub_todo_result = mysqli_query($DB_CONNECTION, "SELECT * FROM sub_todo WHERE todo_id = '$id'");

    render_todo_modal($todo, $sub_todo_result);
  }
  echo "</ul>";
}
?>

<div class="px-3 pt-3 w-100" style="background: radial-gradient(circle, rgba(244,206,207,0.2) 0%, rgba(182,233,219,0.01) 100%);">
  <div class="p-3 h-100 overflow-auto ">
    <?php
    $folder_id = isset($_GET["id"]) ? $_GET["id"] : "";

    if (isset($_GET["id"])) {
      $query_folder = mysqli_query($DB_CONNECTION, "select * from folder where id = $folder_id limit 1");
      while ($folder = mysqli_fetch_assoc($query_folder)) {
        $folder_name = $folder["name"];
      }
    } else {
      $folder_name = "All";
    }
    ?>

    <?php
    if ($folder_id) {
      $query_todo_with_folder = mysqli_query($DB_CONNECTION, "SELECT * FROM todo WHERE user_id = 1 AND folder_id = $folder_id");

      echo "<h3 class='d-flex align-items-center'>";
      echo $folder_name;
      echo "<span class='badge bg-primary rounded-pill ms-2'>" . mysqli_num_rows($query_todo_with_folder) . "</span>";
      echo "</h3>";

      echo "<div class='pe-2 h-100 todo-list d-flex flex-column justify-content-between'>";
      render_todo_card($DB_CONNECTION, $query_todo_with_folder);
    } else {
      $query_all_todos = mysqli_query($DB_CONNECTION, "SELECT * FROM todo WHERE user_id = 1");

      echo "<h3 class='d-flex align-items-center'>";
      echo $folder_name;
      echo "<span class='badge bg-primary rounded-pill ms-2'>" . mysqli_num_rows($query_all_todos) . "</span>";
      echo "</h3>";

      echo "<div class='pe-2 h-100 todo-list d-flex flex-column justify-content-between'>";
      render_todo_card($DB_CONNECTION, $query_all_todos);
    }
    ?>
    <?php
    include_once('src/components/AddTodo.php')
    ?>
  </div>
</div>
</div>

</div>

<style>
  .todo-list {
    overflow-y: auto;
    max-height: 90vh;
  }

  .todo-item:hover {
    background-color: #eef6f4;
  }

  #delete-todo-button {
    background-color: #fdece7;
    border: 1px solid #eceeef;
  }

  #delete-todo-button:hover {
    background-color: #f2724d;
  }
</style>
