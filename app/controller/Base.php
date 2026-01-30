<?php

namespace app\controller;

use app\trait\Report;
use app\trait\Template;

abstract class Base
{
    use Template, Report;
}