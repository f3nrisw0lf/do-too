<div class="d-flex justify-content-center align-self-center h-100">
  <main class="w-50 d-flex flex-column gap-4" style="resize: both; overflow: auto;">
    <?php include_once('src/components/Nav.php'); ?>

    <form class="card p-5 m-2 shadow-sm">
      <h2 class="text-center"><strong>Signup</strong></h2>
      <div class="mb-3">
        <label for="exampleInputEmail1" class="form-label">Username</label>
        <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
      </div>
      <div class="mb-3">
        <label for="exampleInputEmail1" class="form-label">Email address</label>
        <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
        <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
      </div>
      <div class="mb-3">
        <label for="exampleInputPassword1" class="form-label">Password</label>
        <input type="password" class="form-control" id="exampleInputPassword1">
      </div>
      <button type="submit" class="btn btn-primary">Submit</button>
      <a href="/Signup" class="text-center mt-2">Already have an account?</a>
    </form>
  </main>
</div>
