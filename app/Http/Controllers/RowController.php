<?php

namespace App\Http\Controllers;

use App\Models\Row;
use Illuminate\Http\Request;

class RowController extends Controller
{
    public function index() {
        $rows = Row::query()
            ->get()
            ->toArray();

        $rows = collect($rows)
            ->groupBy('date')
            ->toArray();

        return view('rows', ['rows' => $rows]);
    }
}
