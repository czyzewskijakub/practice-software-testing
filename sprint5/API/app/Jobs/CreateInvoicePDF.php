<?php

namespace App\Jobs;

use App\Models\Download;
use App\Models\Invoice;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CreateInvoicePDF implements ShouldQueue {
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $id;

    /**
     * Create a new job instance.
     */
    public function __construct($id) {
        $this->id = $id;
    }

    /**
     * Execute the job.
     */
    public function handle(): void {
        Download::where('name', $this->id)
        ->update(['status' => 'IN_PROGRESS']);

        $invoice = Invoice::with('invoicelines', 'invoicelines.product')->where('invoice_number', $this->id)->first();

        $pdf = PDF::loadView('invoice', ['invoice' => $invoice])->setPaper('legal', 'portrait');
        $fileName = sprintf('%s.pdf',
            $invoice['invoice_number']
        );

        $pdfFilePath = '/invoices/' . $fileName;
        Storage::disk('local')->put($pdfFilePath, $pdf->output());

        Download::where('name', $this->id)
            ->update(['status' => 'COMPLETED']);
    }

}
