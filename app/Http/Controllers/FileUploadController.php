<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImportFileUploadRequest;
use App\Models\Account;
use App\Models\Import;
use App\Models\Transaction;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class FileUploadController extends Controller
{
    private function convertBytesToMegaBytes(float|int $megabytes): float
    {
        return $megabytes / (1e6);
    }

    public function store(ImportFileUploadRequest $request)
    {
        $file = $request->validated()['file'];
        $csv_lines = $request->get('file');
        $this->saveTransactions($csv_lines, $request->get('date'));
        $name = $file->getClientOriginalName();
        $sizeInMb = $this->convertBytesToMegaBytes($file->getSize());
        Log::info("File name: $name, file size $sizeInMb mb");
        return redirect('/')->with('message', "File $name successfully uploaded!");
    }

    private function saveTransactions(array $transactions, Carbon $date)
    {
        $import = Import::create([
            'transactions_date' => $date,
            'user_id' => Auth::user()->id,
        ]);
        foreach ($transactions as $transaction) {
            $transaction = collect($transaction);
            $origin_data = $transaction->take(3);
            $origin_account = Account::firstOrCreate([
                'bank_name' => $origin_data[0],
                'branch' => $origin_data[1],
                'number' => $origin_data[2],
            ]);
            $destiny_data = $transaction->slice(3, 3)->values();
            $destiny_account = Account::firstOrCreate([
                'bank_name' => $destiny_data[0],
                'branch' => $destiny_data[1],
                'number' => $destiny_data[2],
            ]);
            Transaction::create([
                'origin_account_id' => $origin_account->id,
                'destiny_account_id' => $destiny_account->id,
                'import_id' => $import->id,
                'amount' => (int) $transaction[6],
            ]);
        }
    }
}
