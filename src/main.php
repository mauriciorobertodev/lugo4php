<?php

use Lugo4php\Vector2D;
use Lugo\Point as LugoPoint;

require 'vendor/autoload.php';

 $vec = Vector2D::fromLugoPoint(new LugoPoint());

 dd($vec);