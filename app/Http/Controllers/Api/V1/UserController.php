<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Auth\Events\Registered;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Encoders\WebpEncoder;

class UserController extends Controller
{
    // Mendapatkan data user
    public function me()
    {
        $user = Auth::user();

        if ($user->google_id) {
            $user->avatar = $user->avatar;
        } else {
            $user->avatar = $user->avatar
                ? asset('storage/' . $user->avatar)
                : null;
        }

        return response()->json($user);
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'name'   => 'required|string|max:255',
            'email'  => 'required|email|unique:users,email,' . $user->id,
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Update nama & email
        $user->name  = $request->name;
        $user->email = $request->email;

        // Update foto jika ada
        if ($request->hasFile('avatar')) {
            // Hapus foto lama jika ada
            if ($user->avatar && file_exists(storage_path('app/public/' . $user->avatar))) {
                unlink(storage_path('app/public/' . $user->avatar));
            }

            $avatar = $request->file('avatar');
            $avatarName = time() . '_' . $avatar->getClientOriginalName();
            $avatarPath = $avatar->storeAs('uploads/profile', $avatarName, 'public');
            $user->avatar = $avatarPath;
        }

        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Profil berhasil diperbarui',
            'user'    => $user
        ]);
    }

    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'old_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Cek password lama
        if (!Hash::check($request->old_password, $user->password)) {
            return response()->json(['message' => 'Password lama tidak cocok'], 400);
        }

        // Update password
        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Password berhasil diperbarui',
        ]);
    }
    // Akhir dari menampilkan data user

    public function register(Request $request)
    {
        $validasi = Validator::make($request->all(), [
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|unique:users,email',
            'password' => 'required|string|min:8',
            'avatar'   => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($validasi->fails()) {
            return response()->json(['errors' => $validasi->errors()], 422);
        }

        $avatarPath = null;

        if ($request->hasFile('avatar')) {
            $avatar = $request->file('avatar');
            $avatarName = time() . '.webp';

            $manager = new ImageManager(new Driver());

            $image = $manager->read($avatar->getRealPath());

            $savePath = storage_path('app/public/uploads/profile/' . $avatarName);
            if (!file_exists(dirname($savePath))) {
                mkdir(dirname($savePath), 0777, true);
            }

            $image->scaleDown(width: 500)
                ->encode(new WebpEncoder(quality: 70))
                ->save($savePath);

            $avatarPath = 'uploads/profile/' . $avatarName;
        }

        // Simpan user baru
        $user = User::create([
            'avatar'         => $avatarPath,
            'name'           => $request->name,
            'email'          => $request->email,
            'password'       => Hash::make($request->password),
            'remember_token' => Str::random(10),
        ]);

        event(new Registered($user));

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'User created. Please verify your email.',
            'token' => $token,
            'user' => $user,
        ], 201);
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (!Auth::attempt($credentials)) {
            return response()->json(['message' => 'Email atau password salah']);
        }
        $user = Auth::user();
        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json(['message' => 'Anda berhasil login', 'user' => $user, 'token' => $token], 200);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Berhasil logout']);
    }
    // End Register, Login, and Logout Methods


    // Google Login Methods
    public function redirectToGoogle()
    {
        return Socialite::driver('google')
            ->stateless()
            ->with(['prompt' => 'select_account'])
            ->redirect();
    }

    public function handleGoogleCallback()
    {
        $googleUser = Socialite::driver('google')->stateless()->user();

        $user = User::where('email', $googleUser->getEmail())->first();
        $statusCode = 200;
        $message = 'User logged in successfully';

        if (!$user) {
            $user = User::create([
                'avatar' => $googleUser->getAvatar(),
                'name' => $googleUser->getName(),
                'email' => $googleUser->getEmail(),
                'google_id' => $googleUser->getId(),
                'email_verified_at' => now(),
                'password' => bcrypt(uniqid())
            ]);
            $statusCode = 201;
            $message = 'User created and logged in successfully';
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        $frontendUrl = env('FRONTEND_URL') . '/?token=' . $token;

        return redirect($frontendUrl);
    }
    // End Google Login Methods
}
