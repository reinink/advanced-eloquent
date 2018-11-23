<?php

namespace Tests;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ServiceProviderTest extends TestCase
{
    protected function setUp()
    {
        parent::setUp();

        DB::table('users')->insert([
            ['name' => 'Jonathan Reinink', 'email' => 'jonathan@reinink.ca'],
            ['name' => 'Taylor Otwell', 'email' => 'taylor@laravel.com'],
        ]);

        DB::table('logins')->insert([
            ['user_id' => 1, 'created_at' => Carbon::parse('2018-01-01 07:00:00')],
            ['user_id' => 1, 'created_at' => Carbon::parse('2018-01-01 08:00:00')],
            ['user_id' => 2, 'created_at' => Carbon::parse('2018-01-01 07:00:01')],
            ['user_id' => 2, 'created_at' => Carbon::parse('2018-01-01 08:00:01')],
        ]);
    }

    public function test_sub_select()
    {
        $users = DB::table('users')->addSubSelect('last_login_at', DB::table('logins')
            ->select('created_at')
            ->whereColumn('user_id', 'users.id')
            ->latest()
        )->get();

        $this->assertSame($users->count(), 2);
        $this->assertSame($users->first()->name, 'Jonathan Reinink');
        $this->assertSame($users->first()->email, 'jonathan@reinink.ca');
        $this->assertSame($users->first()->last_login_at, '2018-01-01 08:00:00');
        $this->assertSame($users->last()->name, 'Taylor Otwell');
        $this->assertSame($users->last()->email, 'taylor@laravel.com');
        $this->assertSame($users->last()->last_login_at, '2018-01-01 08:00:01');
    }

    public function test_order_by_sub_desc()
    {
        $users = DB::table('users')->orderBySub(DB::table('logins')
            ->select('created_at')
            ->whereColumn('user_id', 'users.id')
            ->latest(), 'desc'
        )->get();

        $this->assertSame($users->count(), 2);
        $this->assertSame($users->first()->name, 'Taylor Otwell');
        $this->assertSame($users->last()->name, 'Jonathan Reinink');
    }

    public function test_order_by_sub_asc()
    {
        $users = DB::table('users')->orderBySub(DB::table('logins')
            ->select('created_at')
            ->whereColumn('user_id', 'users.id')
            ->latest(), 'asc'
        )->get();

        $this->assertSame($users->count(), 2);
        $this->assertSame($users->first()->name, 'Jonathan Reinink');
        $this->assertSame($users->last()->name, 'Taylor Otwell');
    }
}
