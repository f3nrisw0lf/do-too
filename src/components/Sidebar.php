<div class="d-none d-md-flex flex-column flex-shrink-0 p-3 bg-light vh-100" style="width: 280px;">
  <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto link-dark text-decoration-none">
    <!-- <svg class="bi pe-none me-2" width="40" height="32">
      <use xlink:href="#bootstrap"></use>
    </svg> -->
    <!-- <span class="fs-4"><strong>Do-too</strong></span> -->
    <h2 class="m-0">Do-too</h2>
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

    echo "All";
    echo "</a>";
    echo "</li>";
    // Render Accordions on Folders
    while ($folder = mysqli_fetch_assoc($folder_user_query)) {
      $id = $folder["id"];
      $name = $folder["name"];
      $todo_by_folder_query = mysqli_query($DB_CONNECTION, "SELECT * FROM todo WHERE folder_id = '$id'");

      echo "<li class='nav-item'>";

      if ($folder_id == $id)
        echo "<a href='?id=$id' class='nav-link active' aria-current='page'>";
      else
        echo "<a href='?id=$id' class='nav-link' aria-current='page'>";

      echo $name;
      echo "</a>";
      echo "</li>";
    }
    ?>
  </ul>
  <hr>
  <div class="dropdown">
    <a href="#" class="d-flex align-items-center link-dark text-decoration-none dropdown-toggle" id="dropdownUser2" data-bs-toggle="dropdown" aria-expanded="false">
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
      <li><a class="dropdown-item" href="#">Sign out</a></li>
    </ul>
  </div>
</div>
