<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class AdminController extends Controller
{
    public function index()
    {

        // 1. Calculate Statistics
        $totalRevenue = Order::where('status', 'delivered')->sum('total');
        $ordersCount = Order::where('status', '!=', 'cancelled')->count();
        $productsCount = Product::count();
        $customersCount = User::where('is_admin', false)->count();
        if ($customersCount === 0) {
            $customersCount = User::count(); // Fallback if no non-admin users
        }

        $stats = [
            [
                'title' => 'Total Revenue',
                'icon' => 'ti ti-currency-dollar',
                'value' => '$' . number_format($totalRevenue, 2),
                'trend' => 'up',
                'change' => '+12.5%',
                'desc' => 'from last month',
            ],
            [
                'title' => 'Total Orders',
                'icon' => 'ti ti-shopping-cart',
                'value' => $ordersCount,
                'trend' => 'up',
                'change' => '+8.2%',
                'desc' => 'from last week',
            ],
            [
                'title' => 'Active Products',
                'icon' => 'ti ti-shirt',
                'value' => $productsCount,
                'trend' => 'up',
                'change' => '+4.3%',
                'desc' => 'added recently',
            ],
            [
                'title' => 'Total Customers',
                'icon' => 'ti ti-users',
                'value' => $customersCount,
                'trend' => 'up',
                'change' => '+15.3%',
                'desc' => 'new signups this month',
            ],
        ];

        // 2. Fetch Recent Orders
        $dbRecentOrders = Order::with('items')->latest()->take(6)->get();
        $recentOrders = $dbRecentOrders->map(function ($order) {
            $itemNames = $order->items->pluck('product_title')->toArray();
            $productDesc = '';
            if (count($itemNames) > 0) {
                $productDesc = $itemNames[0];
                if (count($itemNames) > 1) {
                    $productDesc .= ' (+' . (count($itemNames) - 1) . ' more)';
                }
            } else {
                $productDesc = 'No items';
            }

            return [
                'id' => '#' . $order->order_number,
                'customer' => $order->customer_name,
                'email' => $order->customer_email,
                'product' => $productDesc,
                'amount' => '$' . number_format($order->total, 2),
                'status' => $order->status,
                'date' => $order->created_at->format('M d, Y'),
            ];
        })->toArray();

        // 3. System Activities (Mock log for premium dashboard aesthetic)
        $activities = [
            [
                'color' => 'green',
                'icon' => 'ti ti-circle-check-filled',
                'message' => 'Database successfully seeded with demo catalog',
                'time' => 'Just now',
            ],
            [
                'color' => 'blue',
                'icon' => 'ti ti-user-plus',
                'message' => 'New customer registered: Sophea Kem',
                'time' => '10 mins ago',
            ],
            [
                'color' => 'orange',
                'icon' => 'ti ti-shopping-cart-discount',
                'message' => 'Coupon "SUMMER25" was applied to order #ORD-8492',
                'time' => '1 hour ago',
            ],
            [
                'color' => 'red',
                'icon' => 'ti ti-alert-triangle-filled',
                'message' => 'Product "Vintage Suede Bomber Jacket" has low stock (2 left)',
                'time' => '3 hours ago',
            ],
            [
                'color' => 'green',
                'icon' => 'ti ti-credit-card',
                'message' => 'Payment received for Order #ORD-8492 ($120.00 via ABA)',
                'time' => '4 hours ago',
            ]
        ];

        // 4. Popular Products
        $dbPopularProducts = Product::with('category')->orderBy('sales', 'desc')->take(5)->get();
        $popularProducts = $dbPopularProducts->map(function ($product) {
            return [
                'id' => $product->id,
                'title' => $product->title,
                'category' => $product->category ? $product->category->name : 'N/A',
                'price' => '$' . number_format($product->price, 2),
                'sales' => $product->sales,
                'rating' => $product->rating,
                'stock' => $product->stock,
            ];
        })->toArray();
        return view('admin.dashboard.dashboard', compact('stats', 'recentOrders', 'activities', 'popularProducts'));
    }

    public function reports(Request $request)
    {
        $rawReports = Order::where('status', '!=', 'cancelled')
            ->selectRaw('DATE(created_at) as date, COUNT(id) as total_orders, SUM(total) as total_sales')
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->paginate(15);

        $itemsSold = OrderItem::selectRaw('DATE(ec_orders.created_at) as date, SUM(ec_order_items.qty) as total_items')
            ->join('ec_orders', 'ec_orders.id', '=', 'ec_order_items.order_id')
            ->where('ec_orders.status', '!=', 'cancelled')
            ->groupBy('date')
            ->get()
            ->pluck('total_items', 'date');

        return view('admin.reports.index', compact('rawReports', 'itemsSold'));
    }

    public function reportPdf($date)
    {
        $orders = Order::with('items')
            ->where('status', '!=', 'cancelled')
            ->whereDate('created_at', $date)
            ->get();

        $totalSales = $orders->sum('total');
        $totalOrders = $orders->count();
        
        $totalItems = OrderItem::join('ec_orders', 'ec_orders.id', '=', 'ec_order_items.order_id')
            ->where('ec_orders.status', '!=', 'cancelled')
            ->whereDate('ec_orders.created_at', $date)
            ->sum('ec_order_items.qty');

        return view('admin.reports.pdf', compact('orders', 'date', 'totalSales', 'totalOrders', 'totalItems'));
    }

    public function reportAll()
    {
        $orders = Order::with('items')
            ->where('status', '!=', 'cancelled')
            ->orderBy('created_at', 'desc')
            ->get();

        $totalSales = $orders->sum('total');
        $totalOrders = $orders->count();
        
        $totalItems = OrderItem::join('ec_orders', 'ec_orders.id', '=', 'ec_order_items.order_id')
            ->where('ec_orders.status', '!=', 'cancelled')
            ->sum('qty');
        $date = 'All-Time';

        return view('admin.reports.pdf', compact('orders', 'date', 'totalSales', 'totalOrders', 'totalItems'));
    }
}
