<?php

namespace courseProject\src\Http\Actions\Auth;

use courseProject\src\Http\Request;
use courseProject\src\Users\Users;

interface AuthentificationInterface
{
    public function user(Request $request): Users;
}