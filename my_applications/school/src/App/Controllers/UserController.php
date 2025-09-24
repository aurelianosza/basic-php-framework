<?php

namespace App\Controllers;

use App\Entities\User;
use App\Responses\JsonResponse;
use App\Services\RegistrationService;
use Core\Http\Controller;
use Core\Http\Request;
use Core\Validation\Rules\Unique;

class UserController extends Controller {

    public function store(
        Request $request,
        RegistrationService $registrationService,
        JsonResponse $jsonResponse
    )
    {
        $request->validate([
            "name" => ["required", "min_length:5", "max_length:256"],
            "email" => ["required", "email", "max_length:256", new Unique("email", User::repository())],
        ]);

        $user = User::repository()
            ->insert([
                "name" => $request->input("name"),
                "email" => $request->input("email"),
                "registration" => $registrationService->generateRegistrationFomCurrentDate()
            ]);

        return $jsonResponse
            ->withData($user)
            ->respond();
    }

    public function destroy(int $userId, JsonResponse $jsonResponse)
    {
        $user = User::repository()
            ->where("id", $userId)
            ->first();

        if (!$user) {
            return $jsonResponse
                ->respondNotFound();
        }

        $user->delete();

        return $jsonResponse
            ->withData($user)
            ->respond();
    }
}
