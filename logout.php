<?php

// Logs out the admin.
session_start();
session_unset();
session_destroy();
header('Location: /');
