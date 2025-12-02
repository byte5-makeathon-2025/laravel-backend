<?php

namespace App\Http\Controllers;

use App\Models\Wish;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WishSuccessController extends Controller
{
    public function show(int $trackingNumber): View
    {
        $wish = Wish::where('tracking_number', $trackingNumber)->firstOrFail();

        return view('wish-success', [
            'wish' => $wish,
            'trackingNumber' => $trackingNumber,
        ]);
    }
}
