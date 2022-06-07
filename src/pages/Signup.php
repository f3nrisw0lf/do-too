<?php
if (isset($_POST["signup-submit"])) {
  if (empty($_POST["username"])) {
    $error["username"] = "Username Required";
  } else {
    $input["username"] = $_POST["username"];
  }

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
}
?>

<div class="d-flex justify-content-center align-self-center h-100">
  <main class="w-50 d-flex flex-column gap-4" style="resize: both; overflow: auto;">
    <?php include_once('src/components/Nav.php'); ?>

    <form class="card p-5 m-2 shadow-sm needs-validation" method="post" novalidate>
      <h2 class="text-center"><strong>Login</strong></h2>
      <div class="mb-3">
        <label for="exampleInputEmail1" class="form-label">Username</label>
        <input type="email" name="email" class="form-control <?php if (isset($error["username"])) echo "is-invalid";
                                                              else echo "is-valid" ?>" id="exampleInputEmail1"
          aria-describedby="emailHelp"
          value=<?php if (isset($input["username"])) echo htmlentities($input["username"]) ?>>
        <div class="invalid-feedback"><?php if (isset($error["username"])) echo htmlentities($error["username"]); ?>
        </div>
      </div>
      <div class="mb-3">
        <label for="exampleInputEmail1" class="form-label">Email address</label>
        <input type="email" name="email" class="form-control <?php if (isset($error["email"])) echo "is-invalid";
                                                              else echo "is-valid" ?>" id="exampleInputEmail1"
          aria-describedby="emailHelp" value=<?php if (isset($input["email"])) echo htmlentities($input["email"]) ?>>
        <div class="invalid-feedback"><?php if (isset($error["email"])) echo htmlentities($error["email"]); ?></div>
      </div>
      <div class="mb-3">
        <label for="exampleInputPassword1" class="form-label">Password</label>
        <input type="password" name="password" class="form-control <?php if (isset($error["password"])) echo "is-invalid";
                                                                    else echo "is-valid" ?>" id="exampleInputEmail1"
          aria-describedby="passwordHelp"
          value=<?php if (isset($input["password"])) echo htmlentities($input["password"]) ?>>
        <div class="invalid-feedback"><?php if (isset($error["password"])) echo htmlentities($error["password"]); ?>
        </div>
      </div>

      <div class="alert alert-danger <?php if (empty($error["login"])) echo "d-none" ?>" role="alert">
        <?php if (isset($error["login"])) echo $error["login"] ?>
      </div>
      <button type="submit" name="signup-submit" class="btn btn-primary">Submit</button>
      <a href="/Signup" class="text-center mt-2">Don't have an account yet?</a>

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