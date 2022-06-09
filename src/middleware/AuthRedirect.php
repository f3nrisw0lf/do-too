<?php

if (isset($_SESSION["user_id"])) {
  header("/do-too");
} else {
  header("/do-too/login");
}