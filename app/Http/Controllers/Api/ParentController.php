<?php
namespace App\Http\Controllers;

use App\Models\ParentModel;
use Illuminate\Http\Request;

class ParentController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'amount_of_children' => 'required|integer|min(1)',
            'payment_data' => 'required|string',
        ]);

        $parent = ParentModel::create($validated);

        return response()->json([
            'message' => 'Parent registered successfully',
            'data' => $parent
        ], 201);
    }
}
