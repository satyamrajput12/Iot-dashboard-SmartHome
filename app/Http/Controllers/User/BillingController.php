<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BillingController extends Controller
{
    public function index()
    {
        // Simulated data for Billing Dashboard
        $currentPlan = 'Pro';
        $monthlyCost = 9.99;
        $devicesUsed = auth()->user()->devices()->count();
        $deviceLimit = 50;
        
        $invoices = [
            (object)['id' => 'INV-2024-001', 'date' => '2024-05-01', 'amount' => 9.99, 'status' => 'Paid'],
            (object)['id' => 'INV-2024-002', 'date' => '2024-04-01', 'amount' => 9.99, 'status' => 'Paid'],
            (object)['id' => 'INV-2024-003', 'date' => '2024-03-01', 'amount' => 9.99, 'status' => 'Paid'],
        ];

        return view('user.billing.index', compact('currentPlan', 'monthlyCost', 'devicesUsed', 'deviceLimit', 'invoices'));
    }
}
