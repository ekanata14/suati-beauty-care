<?php


namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

// Models
use App\Models\User;
use App\Models\Konsumen;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'username' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
                'tanggal_lahir' => ['required', 'date'],
                'jenis_kelamin' => ['required', 'string'],
                'foto_profil' => ['nullable', 'file', 'image', 'max:2048'],
                'telepon' => ['required', 'string', 'max:15'],
            ]);
            $fotoProfilPath = null;
            if ($request->hasFile('foto_profil')) {
                $fotoProfilPath = $request->file('foto_profil')->store('foto_profil', 'public');
            }

            $user = User::create([
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->email,
                'role' => "pelanggan",
                'password' => Hash::make($request->password),
            ]);

            $konsumen = Konsumen::create([
                'id_user' => $user->id,
                'tanggal_lahir' => $request->tanggal_lahir,
                'jenis_kelamin' => $request->jenis_kelamin,
                'foto_profil' => $fotoProfilPath,
                'telepon' => $request->telepon,
            ]);

            event(new Registered($user));

            Auth::login($user);

            DB::commit();

            return redirect(route('products', absolute: false))->with('success', 'You\'ve been registered, please choose your items');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Registration failed: ' . $e->getMessage()]);
        }
    }
}
