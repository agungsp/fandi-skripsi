<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\File;
use App\Exports\ResultExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\Snappy\Facades\SnappyPdf;
use App\Models\Result;
use App\Models\Transaction;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Cache;

class AnalisaController extends Controller
{
    function __construct(){
        ini_set('max_execution_time', 0);
    }

    public function index()
    {
        $files = File::orderBy('created_at', 'desc')->get();
        return view('client.analisa', compact('files'));
    }

    public function details($file_id)
    {
        $file = File::find($file_id);
        $max_combination = $this->maxCombination($file_id);
        return view('client.analisa_detail', compact('file', 'max_combination'));
    }

    public function rules($file_id)
    {
        $data  = Result::where('file_id', $file_id)->get();
        $rules = collect();
        foreach ($data as $row) {
            $formated = $this->createRulesText($row->antecedent, $row->consequent);
            $rules->push([
                'rules'      => 'Jika membeli ' . $formated[0] . ', Maka juga membeli ' . $formated[1],
                'support'    => $row->support,
                'confidence' => $row->confidence
            ]);
        }

        return DataTables::of($rules)
                         ->addIndexColumn()
                         ->editColumn('support', function ($collection) {
                             return $collection['support'] . ' %';
                         })
                         ->editColumn('confidence', function ($collection) {
                             return $collection['confidence'] . ' %';
                         })
                         ->make(true);
    }

    public function itemsetCombine($file_id, $combination_count = 2)
    {
        $rules              = $this->getRulesItem($file_id);
        $transactions       = $this->getTransactions($file_id);
        $itemsets           = collect();
        $transactions_total = count($transactions);
        foreach ($rules as $rule) {
            if (count($rule) == $combination_count) {
                $rule_count = 0;
                foreach ($transactions as $transaction) {
                    if ($this->containsArray($rule, $transaction)) $rule_count++;
                }
                $rule_support = $rule_count / $transactions_total;
                if (!$itemsets->contains('itemset', implode(', ', $rule))) {
                    $itemsets->push([
                        'itemset' => implode(', ', $rule),
                        'count'   => $rule_count,
                        'support' => $rule_support
                    ]);
                }
            }
        }
        return DataTables::of($itemsets)
                         ->addIndexColumn()
                         ->editColumn('support', function ($collection) {
                             return $collection['support'] . ' %';
                         })
                         ->make(true);
    }

    public function maxCombination($file_id)
    {
        $rules = $this->getRulesItem($file_id);
        $max = 0;
        foreach ($rules as $rule) {
            $max = max($max, count($rule));
        }
        return $max;
    }

    public function associationRule($file_id)
    {
        $data  = Result::where('file_id', $file_id)->get();
        $rules = collect();
        foreach ($data as $row) {
            $formated = $this->createRulesText($row->antecedent, $row->consequent, false);
            $rules->push([
                'association' => 'If ' . $formated[0] . ', then ' . $formated[1],
                'support'     => $row->support,
                'confidence'  => $row->confidence,
                'sxc'         => $row->support * $row->confidence
            ]);
        }

        return DataTables::of($rules->sortByDesc('sxc'))
                         ->addIndexColumn()
                         ->editColumn('support', function ($collection) {
                             return $collection['support'] . ' %';
                         })
                         ->editColumn('confidence', function ($collection) {
                             return $collection['confidence'] . ' %';
                         })
                         ->make(true);
    }

    public function finalResult($file_id)
    {
        $data  = Result::where('file_id', $file_id)->get();
        $file  = File::find($file_id);
        $rules = collect();
        foreach ($data as $row) {
            if ($row->consequent >= $file->consequent) {
                $formated = $this->createRulesText($row->antecedent, $row->consequent, false);
                $rules->push([
                    'association' => 'If ' . $formated[0] . ', then ' . $formated[1],
                    'support'     => $row->support,
                    'confidence'  => $row->confidence,
                    'sxc'         => $row->support * $row->confidence
                ]);
            }
        }

        return DataTables::of($rules->sortByDesc('sxc'))
                         ->addIndexColumn()
                         ->editColumn('support', function ($collection) {
                             return $collection['support'] . ' %';
                         })
                         ->editColumn('confidence', function ($collection) {
                             return $collection['confidence'] . ' %';
                         })
                         ->make(true);
    }

    public function toExcel($file_name)
    {
        $file    = File::where('name', $file_name)->first();
        return Excel::download(new ResultExport($file), explode('.', $file->name)[0] . ' - ' . now()->format('Ymd_his') .'.xlsx');
    }

    public function toPdf($file_name)
    {
        $file = File::where('name', $file_name)->first();
        $data  = Result::where('file_id', $file->id)->limit(10000)->get();
        $rules = collect();
        foreach ($data as $row) {
            if ($row->consequent >= $file->consequent) {
                $formated = $this->createRulesText($row->antecedent, $row->consequent, false);
                $rules->push([
                    'association' => 'If ' . $formated[0] . ', then ' . $formated[1],
                    'support'     => $row->support,
                    'confidence'  => $row->confidence,
                    'sxc'         => $row->support * $row->confidence
                ]);
            }
        }
        $result = $rules->sortByDesc('sxc')->values();
        $pdf = SnappyPdf::loadView('pdf.full', compact('file', 'result'));
        return $pdf->download(explode('.', $file->name)[0] . ' - ' . now()->format('Ymd_his') .'.pdf');
    }

    private function createRulesText($antecedent, $consequent, $changeLabel = true)
    {
        $arr_antecedent = explode(', ', $antecedent);
        $arr_consequent = explode(', ', $consequent);

        $str_antecedent = '';
        for ($i = 0; $i < count($arr_antecedent); $i++) {
            $str_antecedent .= $changeLabel ? Transaction::where('itemset_code', $arr_antecedent[$i])->first()->item : $arr_antecedent[$i];

            if ($i < (count($arr_antecedent) - 2)) {
                $str_antecedent .= ', ';
            }
            else if ($i == (count($arr_antecedent) - 2)) {
                $str_antecedent .= ' dan ';
            }
        }

        $str_consequent = '';
        for ($i = 0; $i < count($arr_consequent); $i++) {
            $str_consequent .= $changeLabel ? Transaction::where('itemset_code', $arr_consequent[$i])->first()->item : $arr_consequent[$i];

            if ($i < (count($arr_consequent) - 2)) {
                $str_consequent .= ', ';
            }
            else if ($i == (count($arr_consequent) - 2)) {
                $str_consequent .= ' dan ';
            }
        }

        return [$str_antecedent, $str_consequent];
    }

    private function getRulesItem($file_id)
    {
        $data = Result::where('file_id', $file_id)->get();
        $rules = collect();
        foreach ($data as $row) {
            $arr_antecedent = explode(', ', $row->antecedent);
            $arr_consequent = explode(', ', $row->consequent);
            $rules->push(array_merge($arr_antecedent, $arr_consequent));
        }
        return $rules;
    }

    private function getTransactions($file_id)
    {
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
        return Cache::get($keyCache);
    }

    private function containsArray(array $searchArray, array $haystack)
    {
        $finded = 0;
        foreach ($searchArray as $item) {
            if (in_array($item, $haystack)) $finded++;
        }
        return $finded == count($searchArray);
    }
}
