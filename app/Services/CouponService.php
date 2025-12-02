<?php

namespace App\Services;

use App\Models\Coupon;
use App\Models\Wish;

class CouponService
{
    /**
     * Checks if a Coupon with the given hash is purchasable and returns status info.
     *
     * @param string $hash
     * @return array
     */
    public function checkCouponHash(string $hash): array
    {
        $coupon = Coupon::where('hash', $hash)->first();
        $result = [
            'isPurchasable' => false,
            'parentId' => null,
            'status' => null,
        ];
        if ($coupon) {
            $result['parentId'] = $coupon->parent_id;
            $wish = Wish::where('coupon_id', $coupon->id)->first();
            if ($wish) {
                $result['status'] = $wish->status;
            } else {
                $result['isPurchasable'] = true;
            }
        }
        return $result;
    }
}

