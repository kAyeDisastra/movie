<?php

// Namespace untuk menentukan lokasi file dalam struktur folder Laravel
namespace App\Models;

// Import class yang dibutuhkan untuk fitur autentikasi dan notifikasi
// use Illuminate\Contracts\Auth\MustVerifyEmail; // Untuk verifikasi email (tidak digunakan)
use Illuminate\Database\Eloquent\Factories\HasFactory; // Untuk membuat factory data dummy
use Illuminate\Foundation\Auth\User as Authenticatable; // Base class untuk user authentication
use Filament\Models\Contracts\FilamentUser; // Interface untuk akses panel admin Filament
use Illuminate\Notifications\Notifiable; // Trait untuk mengirim notifikasi

// Model User yang extends dari Authenticatable dan implements FilamentUser
// Authenticatable = fitur login/logout, FilamentUser = akses ke admin panel
class User extends Authenticatable implements FilamentUser
{
    /** 
     * Trait HasFactory untuk membuat data dummy menggunakan factory
     * Trait Notifiable untuk fitur notifikasi (email, SMS, dll)
     */
    use HasFactory, Notifiable;

    /**
     * Daftar kolom yang boleh diisi secara mass assignment (create/update sekaligus)
     * Mass assignment = User::create(['name' => 'John', 'email' => 'john@email.com'])
     * Kolom yang tidak ada di $fillable tidak bisa diisi dengan cara ini (keamanan)
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',     // Nama lengkap user
        'email',    // Email untuk login
        'password', // Password yang sudah di-hash
        'role',     // Role user (admin, owner, customer)
        'address',  // Alamat user
        'phone'     // Nomor telepon user
    ];

    /**
     * Daftar kolom yang disembunyikan saat data user di-convert ke JSON/array
     * Berguna untuk API response agar data sensitif tidak terlihat
     * Contoh: saat return response()->json($user), password tidak akan muncul
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',      // Password hash tidak boleh terlihat di response
        'remember_token', // Token "Remember Me" untuk login otomatis
    ];

    /**
     * Casting = mengubah tipe data kolom database ke tipe data PHP
     * Laravel otomatis convert data sesuai dengan cast yang didefinisikan
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            // Kolom email_verified_at dari string database jadi Carbon datetime object
            'email_verified_at' => 'datetime',
            // Kolom password otomatis di-hash saat disimpan ke database
            'password' => 'hashed',
        ];
    }

    /**
     * Method untuk mengecek apakah user bisa akses panel admin Filament
     * Method ini dipanggil otomatis oleh Filament saat user coba akses admin panel
     * 
     * @param $panel - Object panel Filament yang ingin diakses
     * @return bool - true jika boleh akses, false jika tidak
     */
    public function canAccessPanel($panel): bool
    {
        // Log untuk debugging - mencatat siapa yang coba akses panel apa
        \Log::info('Panel access check', [
            'panel_id' => $panel->getId(),    // ID panel yang diakses (admin/owner)
            'user_role' => $this->role,       // Role user yang login
            'user_email' => $this->email      // Email user untuk identifikasi
        ]);

        // Jika panel yang diakses adalah 'admin' DAN role user adalah 'admin'
        if ($panel->getId() === 'admin' && $this->role === 'admin') {
            return true; // Boleh akses
        }

        // Jika panel yang diakses adalah 'owner' DAN role user adalah 'owner'
        if ($panel->getId() === 'owner' && $this->role === 'owner') {
            return true; // Boleh akses
        }

        // Selain kondisi di atas, tidak boleh akses
        return false;
    }
}
