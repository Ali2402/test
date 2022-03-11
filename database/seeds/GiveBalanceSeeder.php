<?php

use Illuminate\Database\Seeder;

class GiveBalanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $giveBalance = 200;

        $users = DB::table('users')->get();
        foreach($users as $user)
        {
            DB::table('users')->where('id' , $user->id)->update(['balance' => $user->balance+$giveBalance ]);
            DB::table('logs')->insert([['user_id' => $user->id , 'type' => 2 , 'amount' => $giveBalance , 'ip_address' => 'XXXXXX' , 'user_agent' => 'XXXXXX-UNDETECTED' , 'desc' => 'Free balance from ryujin : '.$giveBalance]]);
        }
    }
}
