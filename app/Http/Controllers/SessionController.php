<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class SessionController extends Controller
{
    public function destroy()
    {
        auth()->logout();

        return redirect('/')->with('success', 'Goodbye!');
    }

    public function create()
    {
        return view('session.create');
    }

    public function store()
    {
        $attributes = request()->validate([
            'email' => ['required', 'email'], // Rule::exists('users','email')
            'password' => ['required']
        ]);

        if (auth()->attempt($attributes)) {
            session()->regenerate();

            return redirect('/')->with('success', 'Welcome Back!');
        }

//        ValidationException::withMessages([
//            'email' => 'Your provided credentials could not be verified'
//        ]);

//        OR

        return back()
            ->withInput()
            ->withErrors(['email' => 'Your provided credentials could not be verified']);
    }
}
