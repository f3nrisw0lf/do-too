<?php
$user_id = $_SESSION["user_id"];
$todo_focus_id = -1;
$folder_id = $_GET["folder_id"] ?? null;
$current_date = date("Y/m/d");

$query_todo = $pdo->query("SELECT * FROM todo WHERE user_id = $user_id " . ($folder_id ? "AND folder_id = $folder_id " : "")  . "ORDER BY is_done");
$folder_name = $folder_id ? $pdo->query("SELECT * FROM folder WHERE id = $folder_id")->fetch(PDO::FETCH_ASSOC)["name"] : "All";
$todo_id = filter_input(INPUT_POST, 'todo-id', FILTER_SANITIZE_NUMBER_INT);

// Create
$sub_todo = filter_input(INPUT_POST, 'sub-todo', FILTER_SANITIZE_ADD_SLASHES);
if (isset($sub_todo)) {
  $pdo->query("INSERT INTO sub_todo(todo_id, content, creation_date) VALUES ('$todo_id', '$sub_todo', '$current_date')");
  $todo_focus_id = $todo_id;
}

// Update
$edit_folder_name = filter_input(INPUT_POST, 'folder-name', FILTER_SANITIZE_ADD_SLASHES);
if (isset($edit_folder_name) and isset($folder_id)) {
  $pdo->query("UPDATE folder SET name = '$edit_folder_name' WHERE id = $folder_id");
  header("Location: ./?folder_id={$folder_id}");
}

$edit_todo_title = filter_input(INPUT_POST, 'edit-todo-title', FILTER_SANITIZE_ADD_SLASHES);
$edit_todo_id = filter_input(INPUT_POST, 'edit-todo-id', FILTER_SANITIZE_NUMBER_INT);
if (isset($edit_todo_title)) {
  $pdo->query("UPDATE todo SET title = '$edit_todo_title' WHERE id = '$edit_todo_id'");
  header("Location: ./");
}

$edit_sub_content = filter_input(INPUT_POST, 'edit-sub-content', FILTER_SANITIZE_ADD_SLASHES);
$edit_sub_id = filter_input(INPUT_POST, 'edit-sub-id', FILTER_SANITIZE_NUMBER_INT);
$focus_id = filter_input(INPUT_POST, 'focus_id', FILTER_SANITIZE_NUMBER_INT);
if (isset($edit_sub_content)) {
  $pdo->query("UPDATE sub_todo SET content = '$edit_sub_content' WHERE id = '$edit_sub_id'");
  $todo_focus_id = $focus_id;
  header("Location: ./");
}

// Delete
$todo_delete_id = filter_input(INPUT_GET, 'delete_id', FILTER_SANITIZE_NUMBER_INT);
if (isset($todo_delete_id)) {
  $pdo->query("DELETE FROM todo WHERE id=$todo_delete_id");
  header("Location: ./");
}

$sub_delete_id = filter_input(INPUT_GET, 'sub_delete_id', FILTER_SANITIZE_NUMBER_INT);
if (isset($sub_delete_id)) {
  $pdo->query("DELETE FROM sub_todo WHERE id=$sub_delete_id");
  header("Location: ./");
}

$folder_delete_id = filter_input(INPUT_GET, 'folder_delete_id', FILTER_SANITIZE_NUMBER_INT);
if (isset($folder_delete_id)) {
  $pdo->query("DELETE FROM folder WHERE id=$folder_delete_id");
  header("Location: ./");
}

// Done/Undone
$done_todo_id = filter_input(INPUT_GET, 'done_id', FILTER_SANITIZE_NUMBER_INT);
if (isset($done_todo_id)) {
  $pdo->query("UPDATE todo SET is_done = NOT is_done WHERE id = $done_todo_id");
  header("Location: ./");
}

$sub_done_id = filter_input(INPUT_GET, 'sub_done_id', FILTER_SANITIZE_NUMBER_INT);
if (isset($sub_done_id)) {
  $pdo->query("UPDATE sub_todo SET is_done = NOT is_done WHERE id = $sub_done_id");
  header("Location: ./");
}

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
} ?>

<div class="px-3 pt-3 w-100" style="background: radial-gradient(circle, rgba(244,206,207,0.3) 0%, rgba(182,233,219,0.01) 100%);">
  <div class="p-3 h-100 overflow-auto ">
    <div class="d-flex justify-content-between my-auto" id="">
      <!-- Folder Name -->
      <?php if ($folder_name != "All") : ?>
        <h3 class='d-flex align-items-center'>
          <form action="" class="" method="post">
            <span class='badge bg-primary rounded-pill'> <?= $query_todo->rowCount() ?> </span>
            <input type="text" class="h3 edit-input w-auto border-0 bg-transparent rounded p-1" name="folder-name" id="" value="<?= htmlentities($folder_name) ?>">
            <input type="submit" class="d-none" value="">
          </form>
        </h3>
      <?php else : ?>
        <h3 class='d-flex align-items-center gap-2'>
          <span class='badge bg-primary rounded-pill'> <?= $query_todo->rowCount() ?> </span>
          <?= htmlentities($folder_name) ?>
        </h3>
      <?php endif ?>
      <!-- Folder Delete Button -->
      <?php if ($folder_id) : ?>
        <a href='./?folder_delete_id=<?= $folder_id ?>' class='p-2 d-flex my-auto' id=''>
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="dark" class="bi bi-trash align-self-center" viewBox="0 0 16 16" id="delete-folder-button">
            <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z" />
            <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z" />
          </svg>
        </a>
      <?php endif ?>
    </div>
    <div class='pe-2 h-100 todo-list d-flex flex-column justify-content-between'>
      <!-- Todo is Empty -->
      <?php if (!$query_todo->rowCount()) : ?>
        <div class="alert border mt-2 d-flex align-items-center shadow-sm" role="alert">
          <h6><?= htmlentities($folder_name) ?> is empty...</h6>
        </div>
      <?php endif ?>
      <ul class='list-group d-flex gap-2 todo-list p-1'>
        <!-- Todo -->
        <?php foreach ($query_todo as $todo) : ?>
          <?php $sub_todo_query = $pdo->query("SELECT * FROM sub_todo WHERE todo_id = {$todo['id']}") ?>
          <div class='d-flex todo-item rounded-2 shadow-sm '>
            <div class="bg-transparent border rounded-start p-2 d-flex">
              <a href='./?done_id=<?= $todo["id"] ?>' class='align-self-center'>
                <?= render_checkbox(htmlentities($todo["is_done"])) ?>
              </a>
            </div>

            <!-- Modal Button -->
            <button type='button' class='w-100 btn-outline-transparent bg-transparent text-black border p-3 border-black border-left-0 d-flex justify-content-between align-items-center ' id='<?= "modal-button-" . $todo["id"] ?>' data-bs-toggle='modal' data-bs-target='<?= "#modal-" . $todo["id"] ?>'>
              <div class='text-start <?= (bool)$todo["is_done"] ? "text-decoration-line-through" : "" ?>'>
                <h5 class='text-dark align-self-center'>
                  <strong> <?= htmlentities($todo["title"]) ?> </strong>
                </h5>
                <p class='opacity-50'> <?= htmlentities($todo["content"] ?? "Description...") ?> </p>
              </div>
              <span class='badge bg-secondary ms-2'> <?= $sub_todo_query->rowCount(); ?></span>
            </button>

            <!-- Delete Button -->
            <a href='./?delete_id=<?= $todo["id"] ?>' class='p-2 border-1 border-left-0 rounded-end d-flex' id='delete-todo-button'>
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="dark" class="bi bi-trash align-self-center" viewBox="0 0 16 16">
                <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z" />
                <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z" />
              </svg>
            </a>
            <!-- Delete Button -->
          </div>

          <!-- Modal -->
          <div class='modal fade' id='<?= "modal-" . $todo["id"] ?>' tabindex='-1' aria-labelledby='modal-<?= $todo["id"] ?>' aria-hidden='true'>
            <div class='modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg'>
              <div class='modal-content px-5 pt-4'>
                <button type='button' class='btn-close align-self-end' data-bs-dismiss='modal' aria-label='Close'></button>
                <div class='modal-body px-0 mb-2'>
                  <form action="" method="post">
                    <input type="text" class="h3 edit-input w-auto border-0 bg-transparent rounded p-1 ms-1" name="edit-todo-title" value="<?= $todo["title"] ?>">
                    <input type="number" class="h3 edit-input w-auto border-0 bg-transparent rounded p-1 d-none" name="edit-todo-id" value="<?= $todo["id"] ?>">
                    <input type="submit" value="" class="d-none">
                  </form>
                  <!-- Sub Todos -->
                  <?php if ($sub_todo_query->rowCount()) : ?>
                    <ul class='list-group list-group-flush justify-content-center d-flex gap-1'>
                      <?php foreach ($sub_todo_query as $sub_todo) : ?>
                        <div class="d-flex justify-content-between border rounded px-2 py-2 align-items-center">
                          <div class="flex-grow-1">
                            <li class='list-group-item border-0 p-0 d-flex gap-2 align-items-center'>
                              <a href='./?sub_done_id=<?= $sub_todo["id"] ?>' class='align-self-center'>
                                <?= render_checkbox($sub_todo["is_done"]) ?>
                              </a>
                              <form action="" class="w-100" method="post">
                                <input type="text" name="edit-sub-content" class="edit-input w-100 border-0 bg-transparent rounded p-1" value="<?= $sub_todo["content"] ?>">
                                <input type="number" class="d-none" name="edit-sub-id" id="" value="<?= $sub_todo["id"] ?>">
                                <input type="number" class="d-none" name="focus-id" id="" value="<?= $todo["id"] ?>">
                                <input type="submit" class="d-none" value="">
                              </form>
                            </li>
                          </div>
                          <div>
                            <a href='./?sub_delete_id=<?= $sub_todo["id"] ?>' class='p-2 border-1 border-left-0 rounded-end d-flex' id=''>
                              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="dark" class="bi bi-trash align-self-center" viewBox="0 0 16 16" id="sub-todo-delete">
                                <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z" />
                                <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z" />
                              </svg>
                            </a>
                          </div>
                        </div>
                      <?php endforeach ?>
                    </ul>
                  <?php else : ?>
                    <div class="alert alert-light align-items-center d-flex gap-2" role="alert">
                      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-info-circle-fill" viewBox="0 0 16 16">
                        <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z" />
                      </svg>
                      No subtodos yet :)
                    </div>

                  <?php endif ?>
                  <form action="" method="post" class="mt-3 p-1">
                    <div class="input-group">
                      <span class="input-group-text" name="sub-todo" id="todo">+ Add</span>
                      <input type="text" class="form-control" name="sub-todo" aria-label="todo" placeholder="Add a subtask" aria-describedby="inputGroup-sizing-default">
                      <input type="hidden" class="form-control" name="todo-id" aria-label="todo" value="<?= $todo["id"] ?>">
                      <button type="submit" class="d-none"></button>
                    </div>
                  </form>
                  <!-- Sub Todos -->
                </div>
                <div class='modal-footer'>
                  <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Close</button>
                  <!-- <button type='button' class='btn btn-primary'>Save changes</button> -->
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

<script>
  window.onload = () => {
    const modalButton = document.querySelector("<?= "#modal-button-{$todo_focus_id}" ?? "" ?>");
    modalButton?.click();
    <?php $todo_focus_id = -1 ?>
  }
</script>


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

  #sub-todo-delete {
    fill: #db6b71;
  }

  #sub-todo-delete:hover {
    fill: #f9959a;
  }

  .edit-input:focus {
    outline-style: solid !important;
    outline-width: 2px;
    outline-color: #f9959a !important;
  }

  #delete-folder-button {
    fill: #f9959a;
  }

  #delete-folder-button:hover {
    fill: red;
  }
</style>
