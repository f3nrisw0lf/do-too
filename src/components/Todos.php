<?php

$user_id = $_SESSION["user_id"];
$folder_id = $_GET["id"] ?? null;

$delete_todo_id = filter_input(INPUT_GET, 'delete_id', FILTER_SANITIZE_NUMBER_INT);
$done_todo_id = filter_input(INPUT_GET, 'done_id', FILTER_SANITIZE_NUMBER_INT);

function render_checkbox($todo_is_done) {
  if ($todo_is_done) return '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="" class="bi bi-check2-square todo-check-box" viewBox="0 0 16 16">
  <path d="M3 14.5A1.5 1.5 0 0 1 1.5 13V3A1.5 1.5 0 0 1 3 1.5h8a.5.5 0 0 1 0 1H3a.5.5 0 0 0-.5.5v10a.5.5 0 0 0 .5.5h10a.5.5 0 0 0 .5-.5V8a.5.5 0 0 1 1 0v5a1.5 1.5 0 0 1-1.5 1.5H3z"/>
  <path d="m8.354 10.354 7-7a.5.5 0 0 0-.708-.708L8 9.293 5.354 6.646a.5.5 0 1 0-.708.708l3 3a.5.5 0 0 0 .708 0z"/>
</svg>';

  return '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="" class="bi bi-app todo-check-box" viewBox="0 0 16 16">
  <path d="M11 2a3 3 0 0 1 3 3v6a3 3 0 0 1-3 3H5a3 3 0 0 1-3-3V5a3 3 0 0 1 3-3h6zM5 1a4 4 0 0 0-4 4v6a4 4 0 0 0 4 4h6a4 4 0 0 0 4-4V5a4 4 0 0 0-4-4H5z"/>
</svg>';
}

if (isset($delete_todo_id)) {
  $pdo->exec("DELETE FROM todo WHERE id=$delete_todo_id");

  header("./");
}

if (isset($done_todo_id)) {
  $pdo->exec("UPDATE todo SET is_done = NOT is_done WHERE id = $done_todo_id");

  header("./");
}

$query_todo = $pdo->query($folder_id ? "SELECT * FROM todo WHERE user_id = $user_id AND folder_id = $folder_id" : "SELECT * FROM todo WHERE user_id = $user_id");

?>

<h3 class='d-flex align-items-center'>
  <?= $folder_name ?? "All" ?>
  <span class='badge bg-primary rounded-pill ms-2'> <?= $query_todo->rowCount() ?> </span>;
</h3>

<div class='pe-2 h-100 todo-list d-flex flex-column justify-content-between'>
  <ul class='list-group d-flex gap-1 todo-list p-1'>
    <?php foreach ($query_todo as $todo) : ?>
    <?php $sub_todo_query = $pdo->query("SELECT * FROM sub_todo WHERE todo_id = {$todo['id']}") ?>

    <div class='d-flex todo-item rounded-2'>
      <div class="bg-transparent border rounded-start p-2 d-flex">
        <a href='./?done_id=<?php $todo["id"] ?>' class='align-self-center'>
          <?= render_checkbox($todo["is_done"]) ?>
        </a>
      </div>
      <button type='button'
        class='w-100 btn-outline-transparent bg-transparent text-black border p-3 border-black border-left-0 d-flex justify-content-between align-items-center " . ((bool)$todo_is_done ? "text-decoration-line-through" : "") . "'
        data-bs-toggle='modal' data-bs-target='#modal-$todo_id'>";
        <div class='text-start'>
          <h5 class='text-dark'>
            <strong class=''> <?= htmlentities($todo["title"]) ?> </strong>
          </h5>
          <p> <?= htmlentities($todo["content"]) ?> </p>
        </div>
        <span class='badge bg-secondary ms-2'> <?= $sub_todo_query->rowCount(); ?></span>
      </button>

      <!-- Sub Todos -->
      <?php foreach ($sub_todo_query as $sub_todo) : ?>
      <ul class='list-group list-group-flush justify-content-center'>
        <div>
          <li class='list-group-item border-0 p-0'>
            <input class='form-check-input' type='checkbox' id='checkboxNoLabel' value='' aria-label='...'>
            <?= $sub_todo["content"] ?>
          </li>
        </div>
      </ul>
      <?php endforeach ?>
      <!-- Sub Todos -->

      <?php endforeach ?>
      <!-- Todo -->

      <?php include_once('src/components/AddTodo.php') ?>
  </ul>
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

.todo-check-box {
  fill: #db6b71;
}

.todo-check-box:hover {
  fill: #f9959a;
}
</style>