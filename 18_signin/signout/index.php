<?php
require_once '../app.php';

use App\Models\AuthUser;

AuthUser::clear();

header('Location: ../signin/');
