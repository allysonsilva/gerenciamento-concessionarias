<?php

namespace App\Models;

use App\Enums\UserStatus;
use Illuminate\Support\Facades\Hash;
use App\Notifications\UserVerifyEmail;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements JWTSubject, MustVerifyEmail
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'status',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
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
            'password' => 'hashed',
            'status' => UserStatus::class,
            'email_verified_at' => 'datetime',
        ];
    }

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::creating(function (self $entity) {
            $entity->status = UserStatus::ENABLE;
        });
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function concessionarias(): HasMany
    {
        return $this->hasMany(Concessionaria::class);
    }

    /**
     * Interact with the user's password.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function password(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => Hash::make($value),
        );
    }

    /**
     * Retrieve common user data.
     *
     * @return array
     */
    public function commonUserData(): array
    {
        return [
            'id' => $this->getKey(),
            'name' => $this->name,
            'email' => $this->email,
            'has_email_verified' => $this->hasVerifiedEmail(),
            'is_enabled' => $this->isEnabled(),
            'created_at' => $this->created_at,
        ];
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier(): int
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * Additional payload data.
     *
     * @return array
     */
    public function getJWTCustomClaims(): array
    {
        return [
            'commonUserData' => $this->commonUserData(),
        ];
    }

    /**
     * Get the e-mail address where password reset links are sent.
     *
     * @return string
     */
    public function getEmailForPasswordReset(): string
    {
        return $this->email;
    }

    /**
     * Send the email verification notification.
     *
     * @return void
     */
    public function sendEmailVerificationNotification()
    {
        $this->notify(new UserVerifyEmail);
    }

    /**
     * Activate the user account.
     *
     * @return bool
     */
    public function activateAccount(): bool
    {
        return $this->forceFill([
            'status' => UserStatus::ENABLE,
        ])->save();
    }

    /**
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->status === UserStatus::ENABLE;
    }
}
