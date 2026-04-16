<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    const PROFESSIONAL_TITLES = [
        'ICT Security Professional & Software Engineer',
        'Hybrid GRC & Technical Security Practitioner',
        'Cyber Security Engineer & Developer',
        'Information Security Specialist',
        'Software Engineer',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'address',
        'linkedin',
        'website',
        'summary',
        'avatar',
        'github',
        'contact_title',
        'contact_subtitle',
        'about_grc_list',
        'about_tech_list',
        'professional_title',
    ];

    public function languages()
    {
        return $this->hasMany(Language::class);
    }

    public function jobProfiles()
    {
        return $this->hasMany(JobProfile::class);
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'about_grc_list' => 'array',
            'about_tech_list' => 'array',
        ];
    }
}
