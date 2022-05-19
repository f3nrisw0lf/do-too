<?php

  $DB_CONNECTION = mysqli_connect("localhost", "root","", "dotoo");

  if(mysqli_connect_errno()){
    echo "MYSQL CONNECTION ERROR: " . mysqli_connect_errno();
  }
