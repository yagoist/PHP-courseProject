<?php

namespace courseProject\src\Http\Actions;

use courseProject\src\Http\Request;
use courseProject\src\Http\Response;

interface ActionInterface
{
    public function handle(Request $request): Response;
}