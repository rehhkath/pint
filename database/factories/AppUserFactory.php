<?php

namespace Database\Factories;

use App\Models\AppUser;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class AppUserFactory extends Factory
{
    protected $model = AppUser::class;

    public function definition(): array
    {
        return [
            'username'   => 'L112',
            'password'   => Hash::make('06853904974'),
            'api_token'  => null,
            'stores'     => ['16235489000175'],
            'role'       => 'manager',
            'cigam_id'   => null,
        ];
    }
}
