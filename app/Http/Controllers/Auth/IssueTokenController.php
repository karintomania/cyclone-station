<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\Auth\IssueTokenService;
use Illuminate\Http\Request;

class IssueTokenController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(
        Request $request,
        IssueTokenService $its,
    ) {
        $result = $its->__invoke($request->header('user'), $request->header('password'));

        if ($result['success']) {
            return $result['token'];
        } else {
            return response('Invalid credential', 401);
        }
    }
}
