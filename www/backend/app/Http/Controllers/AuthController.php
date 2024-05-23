<?php

namespace App\Http\Controllers;

use App\Models\Carbon\CarbonClient;
use App\Models\User;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Throwable;

class AuthController extends Controller
{
    public function login(Request $request): Response
    {
        $fields = $request->validate([
            'name' => 'required|string',
            'password' => 'required|string',
        ]);

        /** @var User $user */
        $user = User::where('name', $fields['name'])->first();

        if (!$user) {
            $user = new User();
        }

        if ($user->role == User::ROLE_ADMIN) {
            if (!Hash::check($fields['password'], $user->password)) {
                return response(
                    ['error' => 'Wrong credentials'],
                    401
                );
            }

            $token = $user->createToken('token')->plainTextToken;

            return response(
                [
                    'role' => 'admin',
                    'token' => $token,
                ],
                200
            );
        }

        try {
            $updateUser = $user->updateUser($fields['name'], $fields['password']);
        } catch (AuthorizationException $e) {
            return response(
                ['error' => $e->getMessage()],
                401
            );
        } catch (Exception|Throwable $e) {
            return response(
                ['error' => $e->getMessage()],
                500
            );
        }

        $token = $user->createToken('token')->plainTextToken;

        return response(
            [
                'result' => $updateUser,
                'role' => 'user',
                'token' => $token,
            ],
            200
        );

    }
    public function logout(Request $request): Response
    {
        auth('sanctum')->user()->currentAccessToken()->delete();

        $response = [
            'user' => auth('sanctum')->user(),
            'message' => 'Logout'
        ];

        return response($response, 201);
    }

    public function getRestoreCode(Request $request): Response
    {
        $fields = $request->validate([
            'name' => 'required|string',
        ]);

        $carbonClient = new CarbonClient();
        $result = $carbonClient->getRestoreCode($fields['name']);

        if (!$result) {
            return response(
                ['error' => 'Error in carbon'],
                500
            );
        }

        return response(
            ['result' => $result],
            200
        );

    }

    public function restore(Request $request): Response
    {
        $fields = $request->validate([
            'carbonUserId' => 'required|string',
            'restoreCode' => 'required|string',
            'newPassword' => 'required|string',
        ]);

        $carbonClient = new CarbonClient();
        $result = $carbonClient->submitRestoreCode($fields['restoreCode'], $fields['carbonUserId'], $fields['newPassword']);

        if (!$result) {
            return response(
                ['error' => 'Error in carbon'],
                500
            );
        }

        return response(
            ['result' => $result],
            200
        );
    }
}
