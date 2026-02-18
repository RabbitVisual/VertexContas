<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, HasRoles, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'cpf',
        'birth_date',
        'phone',
        'photo',
        'membership',
        'status',
        'plan_id',
        'password',
        'last_login_at',
        'last_login_ip',
        'show_assistant',
        'support_access_expires_at',
        'trial_used_at',
    ];

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
            'birth_date' => 'date',
            'last_login_at' => 'datetime',
            'show_assistant' => 'boolean',
            'support_access_expires_at' => 'datetime',
            'trial_used_at' => 'datetime',
        ];
    }

    /**
     * Get the user's full name.
     */
    public function getFullNameAttribute(): string
    {
        return trim("{$this->first_name} {$this->last_name}") ?: (string) $this->email;
    }

    /**
     * Alias for full name (used in views and notifications).
     */
    public function getNameAttribute(): string
    {
        return $this->full_name;
    }

    /**
     * Get the plan the user belongs to (never null: falls back to default free plan).
     */
    public function getPlan(): \Modules\Core\Models\Plan
    {
        if ($this->plan_id && $this->relationLoaded('plan') && $this->plan) {
            return $this->plan;
        }
        $plan = $this->plan()->first();
        if ($plan) {
            return $plan;
        }
        $default = \Modules\Core\Models\Plan::getDefaultFree();
        if ($default) {
            return $default;
        }
        throw new \RuntimeException('No default free plan found. Run migrations and ensure at least one plan with is_free=true exists.');
    }

    /**
     * Get the user's plan (relation; may be null if plan_id not set).
     */
    public function plan(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\Modules\Core\Models\Plan::class, 'plan_id');
    }

    /**
     * Check if user has a Pro or Admin role.
     */
    public function isPro(): bool
    {
        return $this->hasRole('pro_user') || $this->hasRole('admin');
    }

    /**
     * Whether the user has already used the 7-day free trial (e.g. subscribed and then cancelled).
     * Used to prevent granting trial again on re-subscription.
     */
    public function hasUsedTrial(): bool
    {
        return (bool) $this->trial_used_at;
    }

    /**
     * Get the user's photo URL.
     */
    public function getPhotoUrlAttribute(): string
    {
        return $this->photo ? asset('storage/'.$this->photo) : asset('images/default-avatar.svg');
    }

    /**
     * Get the user's photos.
     */
    public function photos()
    {
        return $this->hasMany(\Modules\PanelUser\Models\UserPhoto::class);
    }

    /**
     * Send the password reset notification using Vertex dynamic template (sync for immediate delivery).
     * Logs failures to email_logs and rethrows so Laravel can show the generic error.
     */
    public function sendPasswordResetNotification($token): void
    {
        $url = url(route('password.reset', [
            'token' => $token,
            'email' => $this->getEmailForPasswordReset(),
        ]));

        try {
            \Illuminate\Support\Facades\Mail::to($this->email)
                ->sendNow(new \App\Mail\ResetPasswordEmail($this, $url));
        } catch (\Throwable $e) {
            \Modules\Core\Models\EmailLog::create([
                'user_id' => $this->id,
                'recipient_email' => $this->email,
                'template_key' => 'password_reset',
                'status' => 'failed',
                'error_details' => $e->getMessage(),
                'sent_at' => null,
            ]);
            throw $e;
        }
    }
}
