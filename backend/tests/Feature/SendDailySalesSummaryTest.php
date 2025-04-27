<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;
use App\Models\Sale;
use App\Models\User;
use App\Mail\DailySalesSummary;

class SendDailySalesSummaryTest extends TestCase
{
    use RefreshDatabase;

    public function it_sends_daily_sales_summary_emails()
    {
        Mail::fake();

        $seller = User::factory()->create([
            'email' => 'seller@example.com',
        ]);

        Sale::factory()->count(3)->create([
            'seller_id' => $seller->id,
            'sale_date' => now()->toDateString(),
            'amount' => 100.00,
        ]);

        $this->artisan('app:send-daily-sales-summary')
            ->assertExitCode(0);

        Mail::assertSent(DailySalesSummary::class, function ($mail) use ($seller) {
            return $mail->hasTo($seller->email)
                && $mail->salesCount === 3
                && $mail->totalSalesValue == 300.00
                && $mail->totalCommission == 25.5;
        });

        Mail::assertSent(DailySalesSummary::class, function ($mail) {
            return $mail->hasTo(User::getAdminMail());
        });
    }
}
