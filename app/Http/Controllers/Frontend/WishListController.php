<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SubCategory;
use App\Models\Course;
use App\Models\User;
use App\Models\Wishlist;

use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class WishListController extends Controller
{
    public function AddToWishList(Request $request, $course_id)
    {
        // check login
        if (Auth::check()) {
            // check course đã có trong wishlist của user đó chưa
            $exists = Wishlist::where('user_id', Auth::id())->where('course_id', $course_id)->first();
            // nếu chưa thì thêm
            if (!$exists) {
                Wishlist::insert([
                    'user_id' => Auth::id(),
                    'course_id' => $course_id,
                    'created_at' => Carbon::now(),
                ]);
                return response()->json(['success' => 'Successfully Added on your Wishlist']);
            } else {
                return response()->json(['error' => 'This course Has Already on your withlist']);
            }
        } else {
            return response()->json(['error' => 'Please Login !']);
        }
    }

    public function AllWishlist()
    {

        return view('frontend.wishlist.all_wishlist');
    } // End Method 

    public function GetWishlistCourse()
    {
        // with('course') để lấy dữ liệu của course có liên quancho mỗi mục trong wishlist cùng một lúc, tránh việc truy vấn nhiều lần (N+1 problem).
        $wishlist = Wishlist::with('course')->where('user_id', Auth::id())->latest()->get();
        $wishQty = Wishlist::where('user_id', Auth::id())->count();
        return response()->json(['wishlist' => $wishlist, 'wishQty' => $wishQty]);
    } // End Method 

    public function RemoveWishlist($id)
    {
        Wishlist::where('user_id', Auth::id())->where('id', $id)->delete();
        return response()->json(['success' => 'Successfully Course Remove']);
    }
}
