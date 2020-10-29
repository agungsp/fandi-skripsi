<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Models\Transaction;

class FilesImport implements ToCollection
{

    private $file_id;

    public function __construct($file_id)
    {
        $this->file_id = $file_id;
    }

    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {
        $row_num = 1;
        foreach ($rows as $row) {
            for ($i = 0; $i < count($row); $i++) {
                if ($row[$i] != null) {
                    Transaction::create([
                        'file_id' => $this->file_id,
                        'row_num' => $row_num,
                        'item'    => $row[$i]
                    ]);
                }

                if (($i+1) < count($row)) {
                    if ($row[$i+1] == null) break;
                }
            }
            $row_num++;
        }
    }
}
