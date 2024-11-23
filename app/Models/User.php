<?php
 namespace App\Models;

 use Tymon\JWTAuth\Contracts\JWTSubject;
 use Illuminate\Contracts\Auth\MustVerifyEmail;
 use Illuminate\Database\Eloquent\Factories\HasFactory;
 use Illuminate\Foundation\Auth\User as Authenticatable;
 use Illuminate\Notifications\Notifiable;
 use Laravel\Sanctum\HasApiTokens;
 
 class User extends Authenticatable implements JWTSubject
 {
     use HasApiTokens, HasFactory, Notifiable;
 
     /**
      * The attributes that are mass assignable.
      *
      * @var string[]
      */
     protected $fillable = [
         'name',
         'email',
         'password',
     ];
 
     /**
      * The attributes that should be hidden for serialization.
      *
      * @var array
      */
     protected $hidden = [
         'password',
         'remember_token',
     ];
 
     /**
      * The attributes that should be cast.
      *
      * @var array
      */
     protected $casts = [
         'email_verified_at' => 'datetime',
     ];
 
     /**
      * Get the identifier for JWT authentication.
      *
      * @return void
      */
     public function getJWTIdentifier()
     {
         return $this->getKey();
     }
     
     /**
      * Get custom claims for JWT.
      *
      * @return void
      */
     public function getJWTCustomClaims()
     {
         return [];
     }
 
     /**
      * Define the relationship between User and Role (Many-to-Many).
      *
      * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
      */
     public function roles()
     {
         return $this->belongsToMany(Role::class);
     }
 
     /**
      * Check if the user has a specific role.
      *
      * @param string $role
      * @return bool
      */
     public function hasRole($role)
     {
         return $this->roles->contains('name', $role);
     }
 }
 