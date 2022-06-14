<div class="d-none d-md-flex flex-column flex-shrink-0 flex-grow-0 p-3 vh-100 bg-light" style="width: 280px;">
  <a href="./" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto link-dark text-decoration-none">
    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-card-list" viewBox="0 0 16 16">
      <path d="M14.5 3a.5.5 0 0 1 .5.5v9a.5.5 0 0 1-.5.5h-13a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h13zm-13-1A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2h-13z" />
      <path d="M5 8a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7A.5.5 0 0 1 5 8zm0-2.5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5zm0 5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5zm-1-5a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0zM4 8a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0zm0 2.5a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0z" />
    </svg>
    <h2 class="ms-2 mb-0 text-dark"><strong>DoToo</strong></h2>
  </a>
  <hr>

  <ul class="nav nav-pills flex-column mb-auto">
    <?php
    $folder_id = $_GET["folder_id"] ?? "";
    ?>
    <li class='nav-item'>
      <a href='./' class='nav-link <?= empty($folder_id) ? "active" : "" ?>' aria-current='page'>
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-archive-fill" viewBox="0 0 16 16">
          <path d="M12.643 15C13.979 15 15 13.845 15 12.5V5H1v7.5C1 13.845 2.021 15 3.357 15h9.286zM5.5 7h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1 0-1zM.8 1a.8.8 0 0 0-.8.8V3a.8.8 0 0 0 .8.8h14.4A.8.8 0 0 0 16 3V1.8a.8.8 0 0 0-.8-.8H.8z" />
        </svg>
        <strong> All </strong>
      </a>
    </li>

    <?php foreach ($pdo->query("SELECT * FROM folder WHERE user_id = {$_SESSION["user_id"]}") as $folder) : ?>
      <li class='nav-item d-flex align-items-center'>
        <a href='?folder_id=<?= htmlentities(ucfirst($folder["id"])) ?>' class='nav-link w-100 d-flex align-items-center <?= $folder["id"] ==  $folder_id ? "active" : "text-dark" ?>' aria-current='page'>
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-collection-fill me-2" viewBox="0 0 16 16">

            <path d="M0 13a1.5 1.5 0 0 0 1.5 1.5h13A1.5 1.5 0 0 0 16 13V6a1.5 1.5 0 0 0-1.5-1.5h-13A1.5 1.5 0 0 0 0 6v7zM2 3a.5.5 0 0 0 .5.5h11a.5.5 0 0 0 0-1h-11A.5.5 0 0 0 2 3zm2-2a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 0-1h-7A.5.5 0 0 0 4 1z" />

          </svg>
          <strong class=''> <?= htmlentities($folder["name"]) ?> </strong>
        </a>
      </li>
      </li>
    <?php endforeach ?>
  </ul>
  <form action="" method="post">
    <?php
    if (isset($_POST["new-folder-button"])) {
      $new_folder_count = $pdo->query("SELECT * FROM folder WHERE name = 'New Folder'")->rowCount();

      if ($new_folder_count == 0) {
        $pdo->query("INSERT INTO folder(user_id, name) VALUES({$_SESSION["user_id"]}, 'New Folder')");
      } else $error["add_folder"] = "Rename New Folder First";
    }
    ?>

    <?php if (isset($error["add_folder"])) : ?>
      <div class="alert alert-warning mb-2 d-flex align-items-center gap-2 text-dark" role="alert">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-exclamation-triangle-fill" viewBox="0 0 16 16">
          <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
        </svg>
        <?= htmlentities($error["add_folder"]) ?>
      </div>
    <?php endif ?>

    <input type="submit" name="new-folder-button" class="btn btn-outline-secondary w-100" value="+ New Folder">
  </form>
  <hr>
  <div class="dropdown">
    <a href="#" class="d-flex align-items-center link-dark text-decoration-none dropdown-toggle" id="dropdownUser2" data-bs-toggle="dropdown" aria-expanded="false">
      <img src="https://github.com/mdo.png" alt="" class="rounded-circle me-2" width="32" height="32">
      <strong><?= ucfirst($pdo->query("SELECT * FROM user WHERE id = {$_SESSION['user_id']}")->fetch(PDO::FETCH_ASSOC)["username"]) ?></strong>
    </a>
    <ul class="dropdown-menu text-small shadow" aria-labelledby="dropdownUser2">
      <li><a class="dropdown-item" href="#">Profile</a></li>
      <li>
        <hr class="dropdown-divider">
      </li>
      <li><a class="dropdown-item" href="/do-too/logout">Sign out</a></li>
    </ul>
  </div>
</div>
