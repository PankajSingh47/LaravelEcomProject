<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserController extends Controller
{
    //
    function signin(Request $req){
        $user=User::where(['email'=>$req->email])->first();
        if(!$user||!Hash::check($req->password, $user->password)){
            return "invalid credentials";
        }
        else{
            $req->session()->put('user',$user);
            return redirect('/');
        }
    }

    function signup(Request $req)
    {
        $user= new User;
        $user->name=$req->name;
        $user->email=$req->email;
        $user->password=Hash::make($req->password);
        $user->save();
        return redirect("/signin");

    }
}
