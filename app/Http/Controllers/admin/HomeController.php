<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\TempImage;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class HomeController extends Controller
{
    public function index() {

        $totalOrders = Order::where('status', '!=', 'cancelled')->count();
        $totalProducts = Product::count();
        $totalCustomers = User::where('role', 1)->count();
        $totalRevenue = Order::where('status', '!=', 'cancelled')->sum('grand_total');

        // this month revenue

        $startOfMonth = Carbon::now()->startOfMonth()->format('Y-m-d');
        $currentDate = Carbon::now()->format('Y-m-d');
        $thisMonthName = Carbon::now()->format('M');
        $revenueThisMonth = Order::where('status', '!=', 'cancelled')
                                ->whereDate('created_at', '>=', $startOfMonth)
                                ->whereDate('created_at', '<=', $currentDate)
                                ->sum('grand_total');

        // Last Month Revenue

        $lastMonthStartDate = Carbon::now()->subMonth()->startOfMonth()->format('Y-m-d');
        $lastMonthEndDate = Carbon::now()->subMonth()->endOfMonth()->format('Y-m-d');
        $lastMonthName = Carbon::now()->subMonth()->endOfMonth()->format('M');
        $revenueLastMonth = Order::where('status', '!=', 'cancelled')
                                ->whereDate('created_at', '>=', $lastMonthStartDate)
                                ->whereDate('created_at', '<=', $lastMonthEndDate)
                                ->sum('grand_total');

        // Last 30 Days Revenue      
        
        $lastThirtyDayStartDate = Carbon::now()->subDays(30)->format('Y-m-d');
        $revenueLastThirtyDays = Order::where('status', '!=', 'cancelled')
                                ->whereDate('created_at', '>=', $lastThirtyDayStartDate)
                                ->whereDate('created_at', '<=', $currentDate)
                                ->sum('grand_total');
        
        // Delete Temp Images here
        $dayBeforeToday = Carbon::now()->subDays(1)->format('Y-m-d H:i:s');
        $tempImages = TempImage::where('created_at', '<=', $dayBeforeToday)->get();

        foreach ($tempImages as $tempImage) {
            // echo $tempImage->created_at.'------'.$tempImage->id.'<br>';
             $path = public_path('/temp/'.$tempImage->name);
             $thumbPath = public_path('/temp/thumb/'.$tempImage->name);

            //  Delete Main Image

            if (File::exists($path)) {
                File::delete($path);
            }

            //  Delete Thumb Image

            if (File::exists($thumbPath)) {
                File::delete($thumbPath);
            }

            // TempImage::where('id', $tempImage->id)->delete();

        }

        return view('admin.dashboard', [
            'totalOrders' => $totalOrders,
            'totalProducts' => $totalProducts,
            'totalCustomers' => $totalCustomers,
            'totalRevenue' => $totalRevenue,
            'revenueThisMonth' => $revenueThisMonth,
            'thisMonthName' => $thisMonthName,
            'revenueLastMonth' => $revenueLastMonth,
            'lastMonthName' => $lastMonthName,
            'revenueLastThirtyDays' => $revenueLastThirtyDays,
        ]);

    }

    public function logout() {
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login');
    }
}
