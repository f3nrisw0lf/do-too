<?php

function render_sub_todos($sub_todo_result) {
  echo "<ul class='list-group list-group-flush'>";
  while ($sub_todo = mysqli_fetch_assoc($sub_todo_result)) {
    $sub_todo_content = $sub_todo["content"];
    echo "<li class='list-group-item'>$sub_todo_content</li>";
  }
  echo "</ul>";
}

function render_todo_modal($title, $content, $todo_id, $sub_todo_result) {
  $sub_todo_count = mysqli_num_rows($sub_todo_result);

  echo "<!-- Button trigger modal -->\n";
  echo "<button type=\"button\" class=\"btn btn-outline-light text-black border p-3 border-black rounded-1 d-flex justify-content-between align-items-center \" data-bs-toggle=\"modal\" data-bs-target='#modal-$todo_id'>\n";
  echo "  <div class='text-start'> <strong> $title </strong> <p>$content</p> </div>";
  echo "  <span class='badge bg-primary'>$sub_todo_count</span>";
  echo "</button>\n";
  echo "\n";
  echo "<!-- Modal -->\n";
  echo "<div class=\"modal fade\" id='modal-$todo_id' tabindex=\"-1\" aria-labelledby='modal-$todo_id' aria-hidden=\"true\">\n";
  echo "  <div class=\"modal-dialog\">\n";
  echo "    <div class=\"modal-content\">\n";
  echo "      <div class=\"modal-header\">\n";
  echo "        <h5 class=\"modal-title\" id=\"exampleModalLabel\">Modal title</h5>\n";
  echo "        <button type=\"button\" class=\"btn-close\" data-bs-dismiss=\"modal\" aria-label=\"Close\"></button>\n";
  echo "      </div>\n";
  echo "      <div class=\"modal-body\">\n";
  if ($sub_todo_count) render_sub_todos($sub_todo_result);
  else echo "Does not have a sub-todo :)";
  echo "      </div>\n";
  echo "      <div class=\"modal-footer\">\n";
  echo "        <button type=\"button\" class=\"btn btn-secondary\" data-bs-dismiss=\"modal\">Close</button>\n";
  echo "        <button type=\"button\" class=\"btn btn-primary\">Save changes</button>\n";
  echo "      </div>\n";
  echo "    </div>\n";
  echo "  </div>\n";
  echo "</div>\n";
}

function render_todos($DB_CONNECTION, $todo_result) {
  echo "<ul class='list-group'>";
  while ($todo = mysqli_fetch_assoc($todo_result)) {
    $id = $todo["id"];
    $content = $todo["content"];
    $title = $todo["title"];

    $sub_todo_result = mysqli_query($DB_CONNECTION, "SELECT * FROM sub_todo WHERE todo_id = '$id'");

    render_todo_modal($title, $content, $id, $sub_todo_result);
  }
  echo "</ul>";
}

function render_todo_card($DB_CONNECTION, $todo_result) {
  echo "<ul class='list-group'>";
  while ($todo = mysqli_fetch_assoc($todo_result)) {
    $id = $todo["id"];
    $content = $todo["content"];
    $title = $todo["title"];

    $sub_todo_result = mysqli_query($DB_CONNECTION, "SELECT * FROM sub_todo WHERE todo_id = '$id'");

    render_todo_modal($title, $content, $id, $sub_todo_result);
  }
  echo "</ul>";
}

?>

<div class="mt-4 mx-3">
  <h1> Group </h1>

  <?php
  // NOTED - USER ID must be the id of the logged user
  $folder_user_query = mysqli_query($DB_CONNECTION, "SELECT * FROM folder WHERE user_id = 1");

  // Render Accordions on Folders
  while ($folder = mysqli_fetch_assoc($folder_user_query)) {
    $id = $folder["id"];
    $name = $folder["name"];
    $todo_by_folder_query = mysqli_query($DB_CONNECTION, "SELECT * FROM todo WHERE folder_id = '$id'");

    echo "<div class='accordion' id='accordionExample'>";
    echo "<div class='accordion-item'>";
    echo "<button class='accordion-button' type='button' data-bs-toggle='collapse' data-bs-target='#collapseOne' aria-expanded='true' aria-controls='collapseOne'>";
    echo $name;
    echo "</button>";
    echo "<div id='collapseOne' class='accordion-collapse collapse show' aria-labelledby='headingOne' data-bs-parent='#accordionExample'>";
    render_todos($DB_CONNECTION, $todo_by_folder_query);
    echo "</div>";
  }
  ?>

</div>

<h1 class="mt-3">No Folder</h1>

<?php
$todo_without_folder = mysqli_query($DB_CONNECTION, "SELECT * FROM todo WHERE folder_id IS NULL");

render_todo_card($DB_CONNECTION, $todo_without_folder);

?>
