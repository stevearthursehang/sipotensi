<?php

namespace App\Http\Controllers;

use App\Models\MinatInvestor;
use App\Models\PotensiInvestasi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class InvestorController extends Controller
{
    public function showLoginForm(Request $request)
    {
        if ($request->has('redirect')) {
            session(['url.intended' => $request->redirect]);
        }
        if (Auth::check()) {
            return redirect()->intended(route('investor.dashboard'));
        }
        return view('investor.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            // Link existing inquiries with same email to this user if unlinked
            MinatInvestor::where('email', Auth::user()->email)
                ->whereNull('user_id')
                ->update(['user_id' => Auth::id()]);

            return redirect()->intended(route('investor.dashboard'))
                ->with('success', 'Selamat datang kembali, ' . Auth::user()->name . '!');
        }

        return back()->withErrors([
            'email' => 'Email atau kata sandi yang Anda masukkan tidak sesuai.',
        ])->onlyInput('email');
    }

    public function showRegisterForm(Request $request)
    {
        if ($request->has('redirect')) {
            session(['url.intended' => $request->redirect]);
        }
        if (Auth::check()) {
            return redirect()->intended(route('investor.dashboard'));
        }
        return view('investor.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'telepon' => 'required|string|max:30',
            'perusahaan' => 'nullable|string|max:255',
            'jabatan' => 'nullable|string|max:100',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'telepon' => $validated['telepon'],
            'perusahaan' => $validated['perusahaan'] ?? null,
            'jabatan' => $validated['jabatan'] ?? null,
        ]);

        // Assign 'investor' role if exists, or create it
        try {
            $role = Role::firstOrCreate(['name' => 'investor']);
            $user->assignRole($role);
        } catch (\Throwable $e) {
            // Ignore if role system fails
        }

        Auth::login($user);

        // Link any prior submissions made with this email address
        MinatInvestor::where('email', $user->email)
            ->whereNull('user_id')
            ->update(['user_id' => $user->id]);

        return redirect()->intended(route('investor.dashboard'))
            ->with('success', 'Registrasi Akun Investor Berhasil! Selamat datang di Portal Penanaman Modal Sangihe.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('info', 'Anda telah keluar dari Portal Investor.');
    }

    public function dashboard()
    {
        $user = Auth::user();

        // Get all inquiries belonging to this investor
        $inquiries = MinatInvestor::with(['potensiInvestasi.kelurahan'])
            ->where(function ($q) use ($user) {
                $q->where('user_id', $user->id)
                  ->orWhere('email', $user->email);
            })
            ->latest()
            ->get();

        $stats = [
            'total' => $inquiries->count(),
            'diajukan' => $inquiries->where('status', 'diajukan')->count(),
            'sedang_ditinjau' => $inquiries->where('status', 'sedang_ditinjau')->count(),
            'jadwal_audiensi' => $inquiries->where('status', 'jadwal_audiensi')->count(),
            'disetujui' => $inquiries->where('status', 'disetujui')->count(),
        ];

        // Recommendations
        $rekomendasiPotensi = PotensiInvestasi::with('kelurahan')
            ->latest()
            ->take(3)
            ->get();

        return view('investor.dashboard', compact('user', 'inquiries', 'stats', 'rekomendasiPotensi'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'telepon' => 'required|string|max:30',
            'perusahaan' => 'nullable|string|max:255',
            'jabatan' => 'nullable|string|max:100',
        ]);

        $user->update($validated);

        return back()->with('success', 'Profil investor Anda berhasil diperbarui!');
    }
}

