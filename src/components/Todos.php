<?php

$user_id = $_SESSION["user_id"];
$folder_id = $_GET["folder_id"] ?? null;

$delete_todo_id = filter_input(INPUT_GET, 'delete_id', FILTER_SANITIZE_NUMBER_INT);
$done_todo_id = filter_input(INPUT_GET, 'done_id', FILTER_SANITIZE_NUMBER_INT);

function render_checkbox($todo_is_done) {
  if ($todo_is_done) {
    return '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="" class="bi bi-check2-square todo-check-box" viewBox="0 0 16 16">
              <path d="M3 14.5A1.5 1.5 0 0 1 1.5 13V3A1.5 1.5 0 0 1 3 1.5h8a.5.5 0 0 1 0 1H3a.5.5 0 0 0-.5.5v10a.5.5 0 0 0 .5.5h10a.5.5 0 0 0 .5-.5V8a.5.5 0 0 1 1 0v5a1.5 1.5 0 0 1-1.5 1.5H3z"/>
              <path d="m8.354 10.354 7-7a.5.5 0 0 0-.708-.708L8 9.293 5.354 6.646a.5.5 0 1 0-.708.708l3 3a.5.5 0 0 0 .708 0z"/>
            </svg>';
  }

  return '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="" class="bi bi-app todo-check-box" viewBox="0 0 16 16">
            <path d="M11 2a3 3 0 0 1 3 3v6a3 3 0 0 1-3 3H5a3 3 0 0 1-3-3V5a3 3 0 0 1 3-3h6zM5 1a4 4 0 0 0-4 4v6a4 4 0 0 0 4 4h6a4 4 0 0 0 4-4V5a4 4 0 0 0-4-4H5z"/>
          </svg>';
}

if (isset($delete_todo_id)) {
  $pdo->query("DELETE FROM todo WHERE id=$delete_todo_id");
}

if (isset($done_todo_id)) {
  $pdo->query("UPDATE todo SET is_done = NOT is_done WHERE id = $done_todo_id");
}

$query_todo = $pdo->query($folder_id ? "SELECT * FROM todo WHERE user_id = $user_id AND folder_id = $folder_id" : "SELECT * FROM todo WHERE user_id = $user_id");

$folder_name = $folder_id ? $pdo->query("SELECT * FROM folder WHERE id = $folder_id")->fetch(PDO::FETCH_ASSOC)["name"] : "All";

?>

<div class="px-3 pt-3 w-100"
  style="background: radial-gradient(circle, rgba(244,206,207,0.3) 0%, rgba(182,233,219,0.01) 100%);">
  <div class="p-3 h-100 overflow-auto ">
    <h3 class='d-flex align-items-center'>
      <?= htmlentities($folder_name) ?>
      <span class='badge bg-primary rounded-pill ms-2'> <?= $query_todo->rowCount() ?> </span>
    </h3>

    <div class='pe-2 h-100 todo-list d-flex flex-column justify-content-between'>
      <?php if (!$query_todo->rowCount()) : ?>
      <div class="alert border mt-1 d-flex align-items-center" role="alert">
        <h6><?= htmlentities($folder_name) ?> is empty...</h6>
      </div>
      <?php endif ?>
      <ul class='list-group d-flex gap-2 todo-list p-1'>
        <!-- Todo -->
        <?php foreach ($query_todo as $todo) : ?>
        <?php $sub_todo_query = $pdo->query("SELECT * FROM sub_todo WHERE todo_id = {$todo['id']}") ?>
        <div class='d-flex todo-item rounded-2 shadow-sm'>
          <div class="bg-transparent border rounded-start p-2 d-flex">
            <a href='./?done_id=<?= $todo["id"] ?>' class='align-self-center'>
              <?= render_checkbox(htmlentities($todo["is_done"])) ?>
            </a>
          </div>

          <!-- Modal Button -->
          <button type='button'
            class='w-100 btn-outline-transparent bg-transparent text-black border p-3 border-black border-left-0 d-flex justify-content-between align-items-center '
            data-bs-toggle='modal' data-bs-target='<?= "#modal-" . $todo["id"] ?>'>
            <div class='text-start <?= (bool)$todo["is_done"] ? "text-decoration-line-through" : "" ?>'>
              <h5 class='text-dark'>
                <strong> <?= htmlentities($todo["title"]) ?> </strong>
              </h5>
              <p> <?= htmlentities($todo["content"]) ?> </p>
            </div>
            <span class='badge bg-secondary ms-2'> <?= $sub_todo_query->rowCount(); ?></span>
          </button>

          <!-- Delete Button -->
          <a href='./?delete_id=<?= $todo["id"] ?>' class='p-2 border-1 border-left-0 rounded-end d-flex'
            id='delete-todo-button'>
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="dark"
              class="bi bi-trash align-self-center" viewBox="0 0 16 16">
              <path
                d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z" />
              <path fill-rule="evenodd"
                d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z" />
            </svg>
          </a>
          <!-- Delete Button -->
        </div>

        <!-- Modal -->
        <div class='modal fade' id='<?= "modal-" . $todo["id"] ?>' tabindex='-1'
          aria-labelledby='modal-<?= $todo["id"] ?>' aria-hidden='true'>
          <div class='modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg'>
            <div class='modal-content px-5 pt-4'>
              <button type='button' class='btn-close align-self-end' data-bs-dismiss='modal'
                aria-label='Close'></button>
              <div class='modal-body px-0 mb-2'>
                <h3><?= htmlentities($todo["title"]) ?? "Title" ?></h3>
                <p><?= htmlentities($todo["content"]) ?></p>
                <!-- Sub Todos -->
                <?php if ($sub_todo_query->rowCount()) : ?>
                <ul class='list-group list-group-flush justify-content-center'>
                  <?php foreach ($sub_todo_query as $sub_todo) : ?>
                  <div>
                    <li class='list-group-item border-0 p-0'>
                      <input class='form-check-input' type='checkbox' id='checkboxNoLabel' value='' aria-label='...'>
                      <?= htmlentities($sub_todo["content"]) ?>
                    </li>
                  </div>
                  <?php endforeach ?>
                </ul>
                <?php else : ?>
                <div class="alert alert-light align-items-center d-flex gap-2" role="alert">
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                    class="bi bi-info-circle-fill" viewBox="0 0 16 16">
                    <path
                      d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z" />
                  </svg>
                  No subtodos yet :)
                </div>

                <?php endif ?>
                <!-- Sub Todos -->
              </div>
              <div class='modal-footer'>
                <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Close</button>
                <button type='button' class='btn btn-primary'>Save changes</button>
              </div>
            </div>
          </div>
        </div>
        <!-- Modal -->

        <?php endforeach ?>
        <!-- Todo -->
      </ul>
      <?php include_once('src/components/AddTodo.php') ?>
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