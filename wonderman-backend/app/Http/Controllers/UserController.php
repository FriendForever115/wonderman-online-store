<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangeAvatarRequest;
use App\Http\Requests\ChangeDataRequest;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Laravolt\Avatar\Avatar;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    public function createAdmin(RegisterRequest $request)
    {
        $this->authorize("is_admin", $request->user());

        $first_name = $request->validated(["first_name"]);
        $last_name = $request->validated(["last_name"]);

        if ($request->exists("avatar")) {

            $filename = strtolower(Str::random(15)) . "." . $request->file("avatar")->extension();
            $url = Storage::putFileAs("public/img/avatars", $request->validated("avatar"), $filename);

        } else {

            $filename = strtolower(Str::random(15)) . ".png";
            $generator = new Avatar();
            $file = $generator->create($first_name . " " . $last_name)->setBackground("#7f00ff")->toBase64();
            $url = Storage::putFileAs("public/img/avatars", $file, $filename);

        }

        $user = User::create([
            "first_name" => $first_name,
            "last_name" => $last_name,
            "email" => $request->validated(["email"]),
            "login" => $request->validated(["login"]),
            "password" => Hash::make($request->validated(["password"])),
            "created" => date("Y-m-d"),
            "avatar" => $url,
            "role_id" => 1
        ]);

        return response(new UserResource($user->load("role")), Response::HTTP_CREATED);
    }

    public function changePassword(ChangePasswordRequest $request)
    {
        /** @var User $user */
        $user = $request->user();

        if (Hash::check($request->validated("old_password"), $user->password)) {
            $user->update(["password" => Hash::make($request->validated("new_password"))]);
            return response(["message" => "success"], Response::HTTP_ACCEPTED);
        }

        return response(["error" => "Old password is incorrect."], Response::HTTP_FORBIDDEN);
    }

    public function changeData(ChangeDataRequest $request)
    {
        /** @var User $user */
        $user = $request->user();
        $user->update($request->validated());
        return response(new UserResource($user->load("role")), Response::HTTP_ACCEPTED);
    }

    public function changeAvatar(ChangeAvatarRequest $request)
    {
        /** @var User $user */
        $user = $request->user();
        $file = $request->validated(["avatar"]);

        $filename = strtolower(Str::random(15)) . "." . $file->extension();

        $new_path = Storage::putFileAs("public/img/avatars", $file, $filename);
        Storage::delete($user->avatar);
        $user->update(["avatar" => $new_path]);

        return response(new UserResource($user->load("role")), Response::HTTP_ACCEPTED);
    }

    public function removeAvatar(Request $request)
    {
        /** @var User $user */
        $user = $request->user();
        Storage::delete($user->avatar);

        $generator = new Avatar();
        $newname = strtolower(Str::random(15)) . ".png";
        $avatar = $generator->create($user->first_name . " " . $user->last_name)->setBackground("#7f00ff")->toBase64();
        $new_path = Storage::putFileAs("public/img/avatars", $avatar, $newname);

        $user->update(["avatar" => $new_path]);

        return response(new UserResource($user->load("role")), Response::HTTP_ACCEPTED);
    }
}
