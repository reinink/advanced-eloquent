<?php

namespace Tests;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ServiceProviderTest extends TestCase
{
    public function test_sub_select()
    {
        DB::table('users')->insert([
            'name' => 'Jonathan Reinink',
            'email' => 'jonathan@reinink.ca',
        ]);

        DB::table('logins')->insert([
            'user_id' => 1,
            'created_at' => Carbon::parse('2018-01-01 08:00:00'),
        ]);

        $users = DB::table('users')->addSubSelect('last_login_at', DB::table('logins')
            ->select('created_at')
            ->whereColumn('user_id', 'users.id')
            ->latest()
        )->get();

        $this->assertSame($users->count(), 1);
        $this->assertSame($users->first()->name, 'Jonathan Reinink');
        $this->assertSame($users->first()->email, 'jonathan@reinink.ca');
        $this->assertSame($users->first()->last_login_at, '2018-01-01 08:00:00');
    }
}
