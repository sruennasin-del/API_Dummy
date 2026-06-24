<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function index()
    {
        // 1. Dashboard Stats Array
        $stats = [
            [
                'title' => 'Total Revenue',
                'value' => '$48,259.50',
                'change' => '+12.5%',
                'trend' => 'up', // up/down
                'icon' => 'ti ti-currency-dollar',
                'desc' => 'vs last month'
            ],
            [
                'title' => 'Active Customers',
                'value' => '3,842',
                'change' => '+8.2%',
                'trend' => 'up',
                'icon' => 'ti ti-users',
                'desc' => 'vs last month'
            ],
            [
                'title' => 'Total Orders',
                'value' => '1,248',
                'change' => '+14.3%',
                'trend' => 'up',
                'icon' => 'ti ti-shopping-cart',
                'desc' => 'vs last month'
            ],
            [
                'title' => 'Conversion Rate',
                'value' => '3.42%',
                'change' => '-1.5%',
                'trend' => 'down',
                'icon' => 'ti ti-chart-bar',
                'desc' => 'vs last month'
            ]
        ];

        // 2. Recent Orders Array
        $recentOrders = [
            [
                'id' => '#ORD-84920',
                'customer' => 'Sophea Kem',
                'email' => 'sophea.kem@example.com',
                'product' => 'iPhone 15 Pro Max',
                'amount' => '$1,299.00',
                'status' => 'Completed',
                'date' => 'June 22, 2026',
            ],
            [
                'id' => '#ORD-84919',
                'customer' => 'Borey Chan',
                'email' => 'borey.chan@example.com',
                'product' => 'Solid Gold Petite Micropave',
                'amount' => '$168.00',
                'status' => 'Processing',
                'date' => 'June 21, 2026',
            ],
            [
                'id' => '#ORD-84918',
                'customer' => 'Dara Srey',
                'email' => 'dara.srey@example.com',
                'product' => 'Silicon Power 256GB SSD',
                'amount' => '$109.00',
                'status' => 'Pending',
                'date' => 'June 21, 2026',
            ],
            [
                'id' => '#ORD-84917',
                'customer' => 'Vanny Seng',
                'email' => 'vanny.seng@example.com',
                'product' => 'SanDisk SSD PLUS 1TB',
                'amount' => '$189.00',
                'status' => 'Completed',
                'date' => 'June 20, 2026',
            ],
            [
                'id' => '#ORD-84916',
                'customer' => 'Narith Phoung',
                'email' => 'narith.phoung@example.com',
                'product' => 'WD 4TB Gaming Drive',
                'amount' => '$220.00',
                'status' => 'Cancelled',
                'date' => 'June 19, 2026',
            ],
        ];

        // 3. Popular Products Array
        $popularProducts = [
            [
                'id' => 1,
                'title' => 'iPhone 15 Pro Max',
                'category' => 'Smartphones',
                'price' => '$1,299.00',
                'sales' => 142,
                'rating' => 4.9,
                'stock' => 12,
            ],
            [
                'id' => 2,
                'title' => 'Solid Gold Petite Micropave',
                'category' => 'Accessories',
                'price' => '$168.00',
                'sales' => 98,
                'rating' => 4.7,
                'stock' => 5,
            ],
            [
                'id' => 3,
                'title' => 'Silicon Power 256GB SSD',
                'category' => 'Electronics',
                'price' => '$109.00',
                'sales' => 84,
                'rating' => 4.5,
                'stock' => 0,
            ],
            [
                'id' => 4,
                'title' => 'Fjallraven - Foldsack No. 1 Backpack',
                'category' => 'Fashion',
                'price' => '$109.95',
                'sales' => 76,
                'rating' => 4.6,
                'stock' => 24,
            ]
        ];

        // 4. Recent Activities Array
        $activities = [
            [
                'time' => '10 mins ago',
                'message' => 'New order #ORD-84920 received from Sophea Kem',
                'type' => 'order',
                'icon' => 'ti ti-shopping-cart',
                'color' => 'orange'
            ],
            [
                'time' => '1 hour ago',
                'message' => 'User Narith Phoung updated their shipping address',
                'type' => 'user',
                'icon' => 'ti ti-user',
                'color' => 'blue'
            ],
            [
                'time' => '3 hours ago',
                'message' => 'Silicon Power 256GB SSD stock went out of stock!',
                'type' => 'stock',
                'icon' => 'ti ti-alert-triangle',
                'color' => 'red'
            ],
            [
                'time' => '1 day ago',
                'message' => 'Backup system completed database sync successfully',
                'type' => 'system',
                'icon' => 'ti ti-device-sdcard',
                'color' => 'green'
            ],
        ];

        return view('admin.dashboard.dashboard', compact('stats', 'recentOrders', 'popularProducts', 'activities'));
    }

    public function users()
    {
        $users = [
            [
                'name' => 'Sophea Kem',
                'email' => 'sophea.kem@example.com',
                'role' => 'Administrator',
                'status' => 'Active',
                'joined_date' => 'Jan 12, 2024',
                'avatar' => 'SK',
            ],
            [
                'name' => 'Borey Chan',
                'email' => 'borey.chan@example.com',
                'role' => 'Editor',
                'status' => 'Active',
                'joined_date' => 'Mar 05, 2025',
                'avatar' => 'BC',
            ],
            [
                'name' => 'Dara Srey',
                'email' => 'dara.srey@example.com',
                'role' => 'Customer',
                'status' => 'Active',
                'joined_date' => 'Jun 21, 2026',
                'avatar' => 'DS',
            ],
            [
                'name' => 'Vanny Seng',
                'email' => 'vanny.seng@example.com',
                'role' => 'Customer',
                'status' => 'Inactive',
                'joined_date' => 'Feb 18, 2025',
                'avatar' => 'VS',
            ],
            [
                'name' => 'Narith Phoung',
                'email' => 'narith.phoung@example.com',
                'role' => 'Customer',
                'status' => 'Active',
                'joined_date' => 'Oct 30, 2024',
                'avatar' => 'NP',
            ],
            [
                'name' => 'Rathana Long',
                'email' => 'rathana.long@example.com',
                'role' => 'Moderator',
                'status' => 'Active',
                'joined_date' => 'Dec 15, 2024',
                'avatar' => 'RL',
            ],
            [
                'name' => 'Chenda Kheng',
                'email' => 'chenda.kheng@example.com',
                'role' => 'Customer',
                'status' => 'Inactive',
                'joined_date' => 'May 20, 2026',
                'avatar' => 'CK',
            ]
        ];

        return view('admin.users', compact('users'));
    }

    public function products()
    {
        $products = [
            [
                'id' => 1,
                'title' => 'iPhone 15 Pro Max',
                'category' => 'Smartphones',
                'price' => '$1,299.00',
                'stock' => 12,
                'sales' => 142,
                'rating' => 4.9,
                'status' => 'In Stock',
            ],
            [
                'id' => 2,
                'title' => 'Solid Gold Petite Micropave',
                'category' => 'Accessories',
                'price' => '$168.00',
                'stock' => 5,
                'sales' => 98,
                'rating' => 4.7,
                'status' => 'Low Stock',
            ],
            [
                'id' => 3,
                'title' => 'Silicon Power 256GB SSD',
                'category' => 'Electronics',
                'price' => '$109.00',
                'stock' => 0,
                'sales' => 84,
                'rating' => 4.5,
                'status' => 'Out of Stock',
            ],
            [
                'id' => 4,
                'title' => 'Fjallraven - Foldsack No. 1 Backpack',
                'category' => 'Fashion',
                'price' => '$109.95',
                'stock' => 24,
                'sales' => 76,
                'rating' => 4.6,
                'status' => 'In Stock',
            ],
            [
                'id' => 5,
                'title' => 'SanDisk SSD PLUS 1TB',
                'category' => 'Electronics',
                'price' => '$189.00',
                'stock' => 40,
                'sales' => 65,
                'rating' => 4.3,
                'status' => 'In Stock',
            ],
            [
                'id' => 6,
                'title' => 'WD 4TB Gaming Drive',
                'category' => 'Electronics',
                'price' => '$220.00',
                'stock' => 18,
                'sales' => 54,
                'rating' => 4.4,
                'status' => 'In Stock',
            ],
        ];

        return view('admin.products', compact('products'));
    }
}

