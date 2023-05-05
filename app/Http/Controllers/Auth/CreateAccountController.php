<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\Auth\CreateAccountService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CreateAccountController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, CreateAccountService $createAccountService): Response
    {
        $result = $createAccountService->__invoke(
            $request->post('name'),
            $request->post('email'),
            $request->post('password'),
        );

        if ($result['success']) {
            return response($result['userId']);
        } else {
            return response('Something went wrong', 500);
        }
    }
}
