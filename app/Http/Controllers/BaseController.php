<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\Respond;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Routing\Controller as LaravelBaseController;

abstract class BaseController extends LaravelBaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests, Respond;
}
