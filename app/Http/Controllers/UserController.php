<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function create(){
        return view('users.register');
    }

    public function store(Request $request){
        $formData=$request->validate([
            'name'=> 'required|min:3',
            'email'=> ['required',Rule::unique('users','email')],
            'password'=>'required|confirmed|min:7',
        ]);
        $formData['password']= bcrypt($formData['password']);
       $user= User::create($formData);
        auth()->login($user);
        return redirect('/')->with('message','User created and logged in!');
    }

    public function logout(Request $request){
        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('message','You have been log out!');

    }

    public function login(){
        return view('users.login');
    }

    public function authenticate(Request $request){
        $formData=$request->validate([
            'email'=> ['required'],
            'password'=>'required',
        ]);
        if (auth()->attempt($formData)){
            $request->session()->regenerate();
            return redirect('/')->with('message','You are now logged in!');
        }
        return back()->withErrors(['email'=>'Invalid Credentials'])->onlyInput('email');
    }


}
