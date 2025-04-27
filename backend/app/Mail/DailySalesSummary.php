<?
namespace App\Mail;

use App\Models\Sale;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DailySalesSummary extends Mailable
{
    use Queueable, SerializesModels;

    public $salesCount;
    public $totalSalesValue;
    public $totalCommission;
    public $admin;

    public function __construct($salesCount, $totalSalesValue, $totalCommission, $admin = false)
    {
        $this->salesCount = $salesCount;
        $this->totalSalesValue = $totalSalesValue;
        $this->totalCommission = $totalCommission;
        $this->admin = $admin;
    }

    public function build()
    {
        return $this->subject('Resumo de Vendas Diárias')
                    ->view('emails.daily_sales_summary');
    }
}
