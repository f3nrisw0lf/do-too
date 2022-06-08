<?php

$email_valid = $password_valid = NULL;
$input = [];

require_once("src/db_connection.php");

if (isset($_POST["login-submit"])) {
  if (empty($_POST["email"])) {
    $error["email"] = "Email Required";
  } else {
    $input["email"] = $_POST["email"];
  }

  if (empty($_POST["password"])) {
    $error["password"] = "Password Required";
  } else {
    $input["password"] = $_POST["password"];
  }

  if (isset($input["email"]) and isset($input["password"])) {
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_SPECIAL_CHARS);
    $login_query = mysqli_query($DB_CONNECTION, "SELECT * FROM user WHERE email = '$email' LIMIT 1");

    // Check if Email Exists
    if (mysqli_num_rows($login_query) == 0) {
      $error["login"] = "Email not existing!";
    } else {
      while ($row = mysqli_fetch_assoc($login_query)) {
        $db_email = $row["email"];
        $db_password = $row["password"];
      }

      // Check if query password is same to the inputted password
      if ($password == $db_password) {
        echo "AUTH";
      }
    };
  }
}
?>
<div class="d-flex justify-content-center h-100">
  <main class="w-50 d-flex flex-column gap-4" style="resize: both; overflow: auto;">
    <?php include_once('src/components/Nav.php'); ?>

    <form class="card p-5 m-2 shadow-sm needs-validation" method="post" novalidate>
      <h2 class="text-center"><strong>Login</strong></h2>
      <div class="mb-3">
        <label for="exampleInputEmail1" class="form-label">Email address</label>
        <input type="email" name="email" class="form-control <?php if (isset($error["email"])) echo "is-invalid";
                                                              else if (isset($input["email"])) echo "is-valid" ?>"
          id="exampleInputEmail1" aria-describedby="emailHelp"
          value=<?php if (isset($input["email"])) echo htmlentities($input["email"]) ?>>
        <div class="invalid-feedback"><?php if (isset($error["email"])) echo htmlentities($error["email"]); ?></div>
      </div>
      <div class="mb-3">
        <label for="exampleInputPassword1" class="form-label">Password</label>
        <input type="password" name="password"
          class="form-control <?php if (isset($error["password"])) echo "is-invalid";
                                                                    else if (isset($input["password"])) echo "is-valid" ?>" id="exampleInputEmail1"
          aria-describedby="passwordHelp"
          value=<?php if (isset($input["password"])) echo htmlentities($input["password"]) ?>>
        <div class="invalid-feedback"><?php if (isset($error["password"])) echo htmlentities($error["password"]); ?>
        </div>
      </div>

      <div class="alert alert-danger <?php if (empty($error["login"])) echo "d-none" ?>" role="alert">
        <?php if (isset($error["login"])) echo $error["login"] ?>
      </div>
      <div class="mb-3 form-check">
        <input type="checkbox" class="form-check-input" id="exampleCheck1">
        <label class="form-check-label" for="exampleCheck1">Remember me</label>
      </div>
      <button type="submit" name="login-submit" class="btn btn-primary">Submit</button>
      <a href="/do-too/signup" class="text-center mt-2">Don't have an account yet?</a>

    </form>

  </main>
</div>

<script>
(() => {
  'use strict'

  // Fetch all the forms we want to apply custom Bootstrap validation styles to
  const forms = document.querySelectorAll('.needs-validation')

  // Loop over them and prevent submission
  Array.from(forms).forEach(form => {
    form.addEventListener('submit', event => {
      if (!form.checkValidity()) {
        event.preventDefault()
        event.stopPropagation()
      }

      form.classList.add('was-validated')
    }, false)
  })
})()
</script>