<?php

namespace Database\Seeders;

use App\Models\Report;
use App\Models\User;
use App\Models\Payment;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportSeeder extends Seeder
{
    public function run(): void
    {
        $owner = User::where('role', 'owner')->first();
        $totalIncome = DB::table('payments')->where('status', 'completed')->sum('total_amount');

        Report::insert([
            'period' => Carbon::now()->format('Y-m'),
            'total_income' => $totalIncome,
            'total_expense' => $totalIncome * 0.3, // 30% dari income
            'owner_id' => $owner->id,
        ]);
    }
}