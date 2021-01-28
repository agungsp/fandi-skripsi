<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Transaction;
use App\Imports\FilesImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\File;

class SetItemsetCode implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $file;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($file_id)
    {
        $this->file = File::find($file_id);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Excel::import(new FilesImport($this->file->id), public_path('excelFiles/'.$this->file->name));
        $transactions = Transaction::where('file_id', $this->file->id)
                                   ->get()
                                   ->groupBy('item');

        $i = 1;
        foreach ($transactions as $item => $elements) {
            Transaction::where('item', $item)
                       ->update(['itemset_code' => "A$i"]);
            $i++;
        }
        $this->file->imported = true;
        $this->file->save();
    }

}
