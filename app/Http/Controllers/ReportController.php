<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Models\Contact;

class ReportController extends Controller
{
    public function contactsByCity(): JsonResponse
    {
        $data = Contact::select('city', DB::raw('count(*) as count'))
            ->whereNotNull('city')
            ->groupBy('city')
            ->orderBy('count', 'desc')
            ->get();

        return response()->json(['data' => $data]);
    }

    public function contactsByState(): JsonResponse
    {
        $data = Contact::select('state', DB::raw('count(*) as count'))
            ->whereNotNull('state')
            ->groupBy('state')
            ->orderBy('count', 'desc')
            ->get();

        return response()->json(['data' => $data]);
    }

}