<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class RegisterController extends Controller
{
    /**
     * Show the registration form.
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Handle a registration request.
     */
    public function register(Request $request)
    {
        // Check if this is the first step (basic info + role)
        if (!$request->has('how_hear_about_us')) {
            // First step validation
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'password' => ['required', 'string', 'min:8', 'confirmed'],
                'terms' => ['required', 'accepted'],
            ]);

            // Store data in session for second step
            session([
                'registration_data' => [
                    'name' => $validated['name'],
                    'email' => $validated['email'],
                    'password' => $validated['password'],
                ]
            ]);

            return redirect()->back()->with('show_hear_about_form', true);
        }

        // Second step - complete registration
        $validated = $request->validate([
            'how_hear_about_us' => ['required', 'string', 'in:friend,social_media,search'],
        ]);

        // Get stored data from session
        $registrationData = session('registration_data');
        
        if (!$registrationData) {
            return redirect()->route('register');
        }

        $user = User::create([
            'name' => $registrationData['name'],
            'email' => $registrationData['email'],
            'password' => Hash::make($registrationData['password']),
            'role' => 'user',
        ]);

        // Clear session data
        session()->forget('registration_data');

        return redirect()->route('login')->with('success', 'Registration successful! Please login to continue.');
    }
}
