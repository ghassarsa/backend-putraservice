<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Mail\VerifyEmail;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\URL;

class MailController extends Controller
{
    public function verifyEmail($id, $hash, Request $request)
    {
        $user = User::findOrFail($id);

        if (! hash_equals($hash, sha1($user->getEmailForVerification()))) {
            abort(403, 'Invalid verification link.');
        }

        if ($user->hasVerifiedEmail()) {
            return redirect(env('FRONTEND_URL') . '/?verified=already');
        }

        $user->markEmailAsVerified();

        event(new Verified($user));

        return redirect(env('FRONTEND_URL') . '/comments?verified=true');
    }

    public function checkVerification(Request $request)
    {
        return response()->json([
            'verified' => $request->user()->hasVerifiedEmail()
        ]);
    }

    public function resend(Request $request)
    {
        $user = $request->user();

        if ($user->hasVerifiedEmail()) {
            return response()->json(['message' => 'Email sudah diverifikasi.'], 400);
        }

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1($user->email)]
        );

        Mail::to($user->email)->send(new VerifyEmail($verificationUrl));

        return response()->json(['message' => 'Link verifikasi baru telah dikirim.']);
    }

    // Password reset
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            return response()->json(['message' => 'Link reset password telah dikirim ke email Anda.'], 200);
        } else {
            return response()->json(['message' => 'Gagal mengirim email reset password.'], 500);
        }
    }

    public function resetpassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->password = bcrypt($password);
                $user->save();
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return response()->json(['message' => 'Password berhasil direset. Silakan login dengan password baru.'], 200);
        } else {
            return response()->json(['message' => 'Token reset password tidak valid atau sudah kadaluarsa.'], 400);
        }
    }
}
