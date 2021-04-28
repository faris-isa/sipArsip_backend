<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\User;

class UserController extends Controller
{
    private $url = "https://m3117063.api.isabot.site/upload/avatar/avatar.png";
    // private $url = "http://127.0.0.1/backend/upload/avatar/avatar.png";
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        $users = User::orderBy('id','asc')->get();
        return response()->json($users);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function create()
    // {

    // }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator  = Validator::make($request->all(), [
            "name" => "required",
            "username"  => "required|regex:/^\S*$/u",
            "password"  => "required|min:8",
            "role" => "required",
        ]);

        if($validator->fails()) {
            return response()->json(["status" => 500, "message" => "validasi error", "errors" => $validator->errors()]);
        }

        // $url = "http://127.0.0.1/backend/upload/avatar/avatar.png";

        $userDataArray = array(
            "name" => $request->name,
            "username" => $request->username,
            "password" => md5($request->password),
            'photo' => $this->url,
        );

        $user_status = User::where("username", $request->username)->first();

        if(!is_null($user_status)) {
           return response()->json(["status" => 400, "message" => "Username sudah didaftarkan !"]);
        }

        $user  = User::create($userDataArray);

        if(!is_null($user)) {
            return response()->json(["status" => 201, "message" => "Akun berhasil didaftarkan !", "data" => $user]);
        } else {
            return response()->json(["status" => 404, "message" => "Gagal didaftarkan, periksa kembali !"]);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::findOrFail($id);
        return response()->json($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator  = Validator::make($request->all(), [
            "name" => "required",
            "username"  => "required|regex:/^\S*$/u",
            "role" => "required",
        ]);

        if($validator->fails()) {
            return response()->json(["status" => 500, "message" => "validasi error", "errors" => $validator->errors()]);
        }

        $userDataArray = array(
            "name" => $request->name,
            "username" => $request->username,
            "role" => $request->role,
        );

        $user  = User::where('id', $id)->update($userDataArray);

        if(!is_null($user)) {
            return response()->json(["status" => 201, "message" => "Akun berhasil diubah !", "data" => $user]);
        } else {
            return response()->json(["status" => 404, "message" => "Gagal diperbarui, periksa kembali !"]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return response()->json($user);
    }

    public function userLogin(Request $request) {

        $validator = Validator::make($request->all(), [
            "username" => "required",
            "password" => "required"
        ]);

        if($validator->fails()) {
            return response()->json(["status" => 400, "validation_error" => $validator->errors()]);
        }

        // check if entered username exists in db
        $username_status = User::where("username", $request->username)->first();

        // if username exists then we will check password for the same email
        if(!is_null($username_status)) {
            $password_status = User::where("username", $request->username)->where("password", md5($request->password))->first();

            // if password is correct
            if(!is_null($password_status)) {
                $user = $this->userDetail($request->username);

                return response()->json(["status" => 200, "message" => "Login berhasil", "data" => $user]);
            }

            else {
                // wrong password
                return response()->json(["status" => 404, "message" => "Gagal login, periksa username dan password !"]);
            }
        }

        else {
            // username didnt registered
            return response()->json(["status" => 404, "message" => "Gagal login, periksa username dan password !"]);
        }
    }

    public function userDetail($username) {
        $user = array();
        if($username != "") {
            $user  =  User::where("username", $username)->first();
            return $user;
        }
    }
}
