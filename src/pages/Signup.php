<?php

$signup_sucess = false;

include_once("src/db_connection.php");

if (isset($_POST["signup-submit"])) {
  if (empty($_POST["username"])) {
    $error["username"] = "Username Required";
  } else {
    $input["username"] = $_POST["username"];
  }

  if (empty($_POST["email"])) {
    $error["email"] = "Email Required";
  } else {
    if (filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL) === false) {
      $error["email"] = "Invalid Email";
    }

    $input["email"] = $_POST["email"];
  }

  if (empty($_POST["password"])) {
    $error["password"] = "Password Required";
  } else {
    $input["password"] = $_POST["password"];
  }

  if (empty($error["username"]) and empty($error["email"]) and empty($error["password"])) {
    $username = $input["username"];
    $email = $input["email"];
    $password = $input["password"];
    $signup_query = $pdo->exec("INSERT INTO user(username, password, email) VALUES('$username', '$password', '$email')");
  }
}
?>

<div class="d-flex justify-content-center align-self-center h-100">
  <main class="w-50 d-flex flex-column gap-4 " style="resize: both; overflow:
    auto;">
    <?php include_once('src/components/Nav.php'); ?>

    <form class="card p-5 m-2 shadow-sm needs-validation <?php if ($signup_query) echo " d-none" ?>" method="post"
      novalidate>
      <h2 class="text-center"><strong>Signup</strong></h2>
      <div class="mb-3">
        <label for="exampleInputEmail1" class="form-label">Username</label>
        <input type="text" name="username"
          class="form-control <?php if (isset($error["username"])) echo "is-invalid";
                                                                else if (isset($input["username"])) echo "is-valid"; ?>" id="exampleInputEmail1"
          aria-describedby="emailHelp"
          value=<?php if (isset($input["username"])) echo htmlentities($input["username"]) ?>>
        <div class="invalid-feedback"><?php if (isset($error["username"])) echo htmlentities($error["username"]); ?>
        </div>
      </div>
      <div class="mb-3">
        <label for="exampleInputEmail1" class="form-label">Email address</label>
        <input type="email" name="email" class="form-control <?php if (isset($error["email"])) echo "is-invalid";
                                                              else if (isset($input["email"])) echo "is-valid"; ?>"
          aria-describedby="emailHelp" value=<?php if (isset($input["email"])) echo htmlentities($input["email"]) ?>>
        <div class="invalid-feedback"><?php if (isset($error["email"])) echo htmlentities($error["email"]); ?></div>
      </div>
      <div class="mb-3">
        <label for="exampleInputPassword1" class="form-label">Password</label>
        <input type="password" name="password"
          class="form-control <?php if (isset($error["password"])) echo "is-invalid";
                                                                    else if (isset($input["password"])) echo "is-valid"; ?>" aria-describedby="passwordHelp"
          value=<?php if (isset($input["password"])) echo htmlentities($input["password"]) ?>>
        <div class="invalid-feedback"><?php if (isset($error["password"])) echo htmlentities($error["password"]); ?>
        </div>
      </div>

      <div class="alert alert-danger <?php if (empty($error["login"])) echo "d-none" ?>" role="alert">
        <?php if (isset($error["login"])) echo $error["login"] ?>
      </div>
      <button type="submit" name="signup-submit" class="btn btn-primary">Submit</button>
      <a href="/do-too/login" class="text-center mt-2">Already have an account?</a>
    </form>
    <div class="alert alert-success <?php echo ($signup_success) ? "d-block" : "d-none" ?>" role="alert">
      Signup success!
      <a href="./" class="text-center">Go to Home...</a>
    </div>
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