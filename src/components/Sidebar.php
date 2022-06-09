<div class="d-none d-md-flex flex-column flex-shrink-0 flex-grow-0 p-3 vh-100 bg-light" style="width: 280px;">
  <a href="./" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto link-dark text-decoration-none">
    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-card-list"
      viewBox="0 0 16 16">
      <path
        d="M14.5 3a.5.5 0 0 1 .5.5v9a.5.5 0 0 1-.5.5h-13a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h13zm-13-1A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2h-13z" />
      <path
        d="M5 8a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7A.5.5 0 0 1 5 8zm0-2.5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5zm0 5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5zm-1-5a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0zM4 8a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0zm0 2.5a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0z" />
    </svg>
    <h2 class="ms-2 mb-0 text-dark"><strong>DoToo</strong></h2>
  </a>
  <hr>

  <ul class="nav nav-pills flex-column mb-auto">
    <?php
    // NOTED - USER ID must be the id of the logged user
    $folder_id = isset($_GET["id"]) ? $_GET["id"] : "";
    $folder_user_query = mysqli_query($DB_CONNECTION, "SELECT * FROM folder WHERE user_id = 1");

    echo "<li class='nav-item'>";

    if ($folder_id == "")
      echo "<a href='./' class='nav-link active' aria-current='page'>";
    else
      echo "<a href='./' class='nav-link' aria-current='page'>";

    echo '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-archive-fill" viewBox="0 0 16 16">';
    echo '<path d="M12.643 15C13.979 15 15 13.845 15 12.5V5H1v7.5C1 13.845 2.021 15 3.357 15h9.286zM5.5 7h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1 0-1zM.8 1a.8.8 0 0 0-.8.8V3a.8.8 0 0 0 .8.8h14.4A.8.8 0 0 0 16 3V1.8a.8.8 0 0 0-.8-.8H.8z"/>';
    echo '</svg>';
    echo "<strong> All </strong>";
    echo "</a>";
    echo "</li>";
    // Render Accordions on Folders
    while ($folder = mysqli_fetch_assoc($folder_user_query)) {
      $id = $folder["id"];
      $name = $folder["name"];
      $todo_by_folder_query = mysqli_query($DB_CONNECTION, "SELECT * FROM todo WHERE folder_id = '$id'");

      echo "<li class='nav-item d-flex align-items-center'>";

      if ($folder_id == $id)
        echo "<a href='?id=$id' class='nav-link w-100 d-flex align-items-center active' aria-current='page'>";
      else
        echo "<a href='?id=$id' class='nav-link w-100 d-flex text-dark align-items-center' aria-current='page'>";

      echo '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-collection-fill me-2" viewBox="0 0 16 16">';
      echo '
            <path d="M0 13a1.5 1.5 0 0 0 1.5 1.5h13A1.5 1.5 0 0 0 16 13V6a1.5 1.5 0 0 0-1.5-1.5h-13A1.5 1.5 0 0 0 0 6v7zM2 3a.5.5 0 0 0 .5.5h11a.5.5 0 0 0 0-1h-11A.5.5 0 0 0 2 3zm2-2a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 0-1h-7A.5.5 0 0 0 4 1z" />';
      echo '
          </svg>';
      echo "<strong> $name </strong>";
      echo "</a>";
      echo "</li>";
    }
    ?>
  </ul>
  <hr>
  <div class="dropdown">
    <a href="#" class="d-flex align-items-center link-dark text-decoration-none dropdown-toggle" id="dropdownUser2"
      data-bs-toggle="dropdown" aria-expanded="false">
      <img src="https://github.com/mdo.png" alt="" class="rounded-circle me-2" width="32" height="32">
      <strong>mdo</strong>
    </a>
    <ul class="dropdown-menu text-small shadow" aria-labelledby="dropdownUser2">
      <li><a class="dropdown-item" href="#">New project...</a></li>
      <li><a class="dropdown-item" href="#">Settings</a></li>
      <li><a class="dropdown-item" href="#">Profile</a></li>
      <li>
        <hr class="dropdown-divider">
      </li>
      <li><a class="dropdown-item" href="/do-too/logout">Sign out</a></li>
    </ul>
  </div>
</div>