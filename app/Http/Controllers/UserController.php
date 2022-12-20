<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserController extends Controller
{
    /* here is a functioning for signing for a user so we will take the details of user and
    varify with user table in database if user exist with email id and their password matched
     with then they can get login otherwise they have take a messege of invalid credentials */
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

    /* here a user can get signup with the email, name and password so after they will make a signin then they can use
    all the private routes and functioning for a user */
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
