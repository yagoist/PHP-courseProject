<?php

namespace courseProject\src\Container;

use Exception;
use Psr\Container\NotFoundExceptionInterface;

class NotFoundException extends Exception
    implements NotFoundExceptionInterface
{

}