<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Http\Resources\UserProfileResource;
use Illuminate\Http\JsonResponse as HttpJsonResponse;

class MeController extends BaseController
{
    /**
     * Show user profile data.
     *
     * @return \App\Http\Resources\UserProfileResource
     */
    public function showProfile(): UserProfileResource
    {
        return (new UserProfileResource(auth()->user()));
    }
}
