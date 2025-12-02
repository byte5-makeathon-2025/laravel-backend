<?php
namespace App\Http\Controllers;

use App\Models\ParentModel;
use App\Models\Coupon;
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
        // Create coupons for each child
        for ($i = 0; $i < $parent->amount_of_children; $i++) {
            Coupon::create([
                'hash' => bin2hex(random_bytes(16)),
                'parent_id' => $parent->id,
            ]);
        }
        return response()->json(['parent' => $parent], 201);
    }
}
