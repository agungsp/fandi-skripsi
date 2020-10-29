<?php

namespace App\Helpers;


use Phpml\Association\Apriori;
use App\Models\Transaction;
use App\Models\File;
use App\Models\Result;


class AprioriHelper {

    public static function run($file_id)
    {
        $row_count = count(Transaction::where('file_id', $file_id)->groupBy('row_num')->pluck('row_num'));
        $rows      = [];
        $labels    = [];
        for ($i = 1; $i <= $row_count; $i++) {
            $row = Transaction::where('file_id', $file_id)->where('row_num', $i)->pluck('item')->toArray();
            array_push($rows, $row);
        }

        $file    = File::find($file_id);
        $apriori = new Apriori($file->support/100, $file->confidence/100);
        $apriori->train($rows, $labels);
        $rules = $apriori->getRules();

        foreach ($rules as $rule) {
            Result::create([
                'file_id'    => $file_id,
                'antecedent' => join(', ', $rule['antecedent']),
                'consequent' => join(', ', $rule['consequent']),
                'support'    => $rule['support'],
                'confidence' => $rule['confidence'],
            ]);
        }
    }
}
