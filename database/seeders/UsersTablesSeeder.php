<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserDetail;
use Illuminate\Database\Seeder;

class UsersTablesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $genders = collect(array_keys(UserDetail::GENDER_MAP));
        for ($i = 1; $i <= 100; $i++) {
            $user = new User([
                'name' => "test{$i}",
                'email' => "test{$i}@co.jp",
                'password' => bcrypt("password{$i}"),
            ]);
            $user->save();
            $user->detail()->create([
                'nickname' => "nickname{$i}",
                'birthday' => now()->addDays($i),
                'gender' => $genders->random(),
                'phone' => "090-1111-2222",
                'postal_code' => '1234567',
                'address' => '東京都千代田区永田町2-4-11 9F'
            ]);
        }
    }
}
