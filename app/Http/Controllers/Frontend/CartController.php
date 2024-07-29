<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Course;
use App\Models\Course_goal;
use App\Models\CourseSection;
use App\Models\CourseLecture;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Gloudemans\Shoppingcart\Facades\Cart;

class CartController extends Controller
{
    public function AddToCart(Request $request, $id)
    {
        $course = Course::find($id);

        // check course có $id đã có trong cart chưa
        // Cart::search nhận vào 1 hàm callback để so sánh id của mỗi mục trong giỏ hàng
        // với id của course cần thêm
        $cartItem = Cart::search(function ($cartItem, $rowId) use ($id) {
            return $cartItem->id === $id;
        });
        // isNotEmpty() kiểm tra xem có bất kỳ mục nào trong giỏ hàng thỏa mãn điều kiện 
        // tìm kiếm hay không.
        if ($cartItem->isNotEmpty()) {
            return response()->json(['error' => 'Course is already in your cart']);
        }

        if ($course->discount_price == NULL) {
            Cart::add([

                'id' => $id,
                'name' => $request->course_name,
                'qty' => 1,
                'price' => $course->selling_price,
                'weight' => 1,
                'options' => [
                    'image' => $course->course_image,
                    'slug' => $request->course_name_slug,
                    'instructor' => $request->instructor,
                ],
            ]);
        } else {

            Cart::add([
                'id' => $id,
                'name' => $request->course_name,
                'qty' => 1,
                'price' => $course->discount_price,
                'weight' => 1,
                'options' => [
                    'image' => $course->course_image,
                    'slug' => $request->course_name_slug,
                    'instructor' => $request->instructor,
                ],
            ]);
        }
        return response()->json(['success' => 'Successfully Added on Your Cart']);
    } // End Method 

    public function LoadListCart()
    {
        $carts = Cart::content();
        $cartTotal = Cart::total();
        $cartQty = Cart::count();

        return response()->json(array(
            'carts' => $carts,
            'cartTotal' => $cartTotal,
            'cartQty' => $cartQty,
        ));
    } // End Method 

    public function RemoveCart($rowId)
    {
        Cart::remove($rowId);
        return response()->json(['success' => 'Course Remove From Cart']);
    } // End Method 

    public function MyCart()
    {

        return view('frontend.mycart.view_mycart');
    } // End Method 


}