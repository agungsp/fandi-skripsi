<?php

namespace App\Helpers;


use Phpml\Association\Apriori;
use App\Models\Transaction;
use App\Models\File;
use App\Models\Result;
use Illuminate\Support\Facades\Cache;


class AprioriHelper {

    public static function run($file_id, $support, $confidence)
    {
        $labels    = [];

        // Caching
        $keyCache = 'file_id:'.$file_id;
        if (!Cache::has($keyCache)) {
            $row_count = count(Transaction::where('file_id', $file_id)->groupBy('row_num')->pluck('row_num'));
            $rows      = [];
            for ($i = 1; $i <= $row_count; $i++) {
                $row = Transaction::where('file_id', $file_id)->where('row_num', $i)->pluck('itemset_code')->toArray();
                array_push($rows, $row);
            }
            Cache::forever($keyCache, $rows);
        }
        $dataCache = Cache::get($keyCache);

        // Apriori Proccess
        $apriori = new Apriori($support, $confidence);
        $apriori->train($dataCache, $labels);
        // dd($apriori->apriori());
        // HASIL
        // =================================
        $rules = $apriori->getRules();// MIN 0,001 : 0,5

        foreach ($rules as $rule) {
            Result::create([
                'file_id'    => $file_id,
                'antecedent' => join(', ', $rule['antecedent']),
                'consequent' => join(', ', $rule['consequent']),
                'support'    => $rule['support'],
                'confidence' => $rule['confidence'],
            ]);
        }

        return true;
    }
}
