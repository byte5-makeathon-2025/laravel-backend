<?php
namespace App\Http\Controllers;

use App\Models\ParentModel;
use Illuminate\Http\Request;

class ParentController extends Controller
{
    /**
     * Store a newly created parent in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'amount_of_children' => 'required|integer|min:0',
            'bank_name' => 'required|string|max:255',
            'iban' => 'required|string|max:34',
            'bic' => 'required|string|max:11',
        ]);

        $parent = ParentModel::create($validated);
        return response()->json(['parent' => $parent], 201);
    }
}
