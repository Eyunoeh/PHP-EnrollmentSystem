<?php
session_start();
session_destroy();
header('Location: ../Template/login.html');
