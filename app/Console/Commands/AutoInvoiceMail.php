<?php

namespace App\Console\Commands;

use App\Models\InvoiceModel;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Console\Command;

class AutoInvoiceMail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:auto-invoice-mail';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically send invoice emails to customers if due amount > 0 and last payment is over 120 days ago';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $invoices = InvoiceModel::with(['shop', 'customer', 'items', 'payments'])
            ->where('mail_sent', false) // Only process unsent mails
            ->get();

        if ($invoices->isEmpty()) {
            $this->info('No invoices to mail.');
            return;
        }

        foreach ($invoices as $invoice) {
            $customerEmail = $invoice->customer->email;

            if (!$customerEmail || !filter_var($customerEmail, FILTER_VALIDATE_EMAIL)) {
                $this->warn("Invalid email for invoice #{$invoice->id}");
                continue;
            }

            // Check if due_amount > 0
            if ($invoice->due_amount <= 0) {
                $this->info("Invoice #{$invoice->id} is fully paid. Skipping email.");
                continue;
            }
            $cemail=$invoice->customer->email;
            if(empty($cemail)){
                $this->info("Invoice #{$invoice->id} has no customer email. Skipping email.");
                continue;
            }

            // Check last payment date
            $lastPayment = $invoice->payments->sortByDesc('payment_date')->first();

            if (!$lastPayment || $lastPayment->payment_date < now()->subDays(300)) {
                // Send email if no payment or last payment > 120 days ago
                try {
                    $pdf = Pdf::loadView(
                        'user_layout.user_billing.invoice_pdf',
                        ['invoice' => $invoice, 'bill' => $invoice]
                    );

                    Mail::send([], [], function ($message) use ($invoice, $customerEmail, $pdf) {
                        $message->to($customerEmail)
                            ->subject('Invoice #' . $invoice->id)
                            ->html(
                                '<p>Dear ' . e($invoice->customer->customer_name) . ',</p>
                                 <p>Please find attached your invoice.</p>
                                 <p>Thank you for your business.</p>
                                 <p>Regards,<br>' . e($invoice->shop->shop_name) . '</p>'
                            )
                            ->attachData(
                                $pdf->output(),
                                'invoice_' . $invoice->id . '.pdf'
                            );
                    });

                    // Mark mail as sent
                    $invoice->update(['mail_sent' => true]);

                    $this->info("Invoice #{$invoice->id} mailed successfully.");
                } catch (\Exception $e) {
                    $this->error("Failed for invoice #{$invoice->id}: " . $e->getMessage());
                }
            } else {
                $this->info("Invoice #{$invoice->id} has recent payment. Skipping email.");
            }
        }
    }
}
