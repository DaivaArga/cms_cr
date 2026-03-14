<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('login');
});

// Development convenience: public route to view admin dashboard without authentication
Route::get('/dev/admin', function () {
    $periods = ['7 days', '30 days', '90 days'];
    $selectedPeriod = request('period', '7 days');

    // FR-D01: Dashboard Overview Cards
    $totalRevenue = 45231;
    $totalTransactions = 1340;
    $totalProductsSold = 3450;
    $avgTransactionValue = 33.78;

    // FR-D02: Sales Charts Data (by period)
    $salesOverviewData = match ($selectedPeriod) {
        '7 days' => [
            ['date' => 'Mon', 'value' => 1200],
            ['date' => 'Tue', 'value' => 1500],
            ['date' => 'Wed', 'value' => 900],
            ['date' => 'Thu', 'value' => 1700],
            ['date' => 'Fri', 'value' => 2000],
            ['date' => 'Sat', 'value' => 2500],
            ['date' => 'Sun', 'value' => 1800],
        ],
        '30 days' => [
            ['date' => 'Week 1', 'value' => 8000],
            ['date' => 'Week 2', 'value' => 9500],
            ['date' => 'Week 3', 'value' => 7800],
            ['date' => 'Week 4', 'value' => 10200],
        ],
        '90 days' => [
            ['date' => 'Jan', 'value' => 25000],
            ['date' => 'Feb', 'value' => 28000],
            ['date' => 'Mar', 'value' => 22500],
        ],
        default => [
            ['date' => '2026-02-24', 'value' => 1200],
            ['date' => '2026-02-25', 'value' => 1500],
            ['date' => '2026-02-26', 'value' => 900],
            ['date' => '2026-02-27', 'value' => 1700],
            ['date' => '2026-02-28', 'value' => 2000],
        ],
    };

    // FR-D02: Monthly Performance Chart
    $monthlySalesData = [
        ['date' => 'Jan', 'value' => 25000],
        ['date' => 'Feb', 'value' => 28000],
        ['date' => 'Mar', 'value' => 22500],
        ['date' => 'Apr', 'value' => 30000],
        ['date' => 'May', 'value' => 27500],
        ['date' => 'Jun', 'value' => 32000],
    ];

    // FR-D03: Top Products
    $topProducts = [
        ['name' => 'Premium Widget Pro', 'quantity' => 342, 'revenue' => 10260, 'contribution' => 22.7],
        ['name' => 'Standard Widget', 'quantity' => 285, 'revenue' => 5700, 'contribution' => 12.6],
        ['name' => 'Deluxe Gadget Plus', 'quantity' => 198, 'revenue' => 7920, 'contribution' => 17.5],
        ['name' => 'Basic Tool Kit', 'quantity' => 165, 'revenue' => 3300, 'contribution' => 7.3],
        ['name' => 'Advanced Package', 'quantity' => 142, 'revenue' => 7100, 'contribution' => 15.7],
    ];

    return view('admin.admin-dashboard', compact('periods', 'selectedPeriod', 'salesOverviewData', 'monthlySalesData', 'topProducts', 'totalRevenue', 'totalTransactions', 'totalProductsSold', 'avgTransactionValue'));
});

// Shortcut to simulate a login that redirects to the dev admin dashboard
Route::get('/login-as-admin', function () {
    return redirect('/dev/admin');
});

// Sales Reports Module
Route::get('/dev/reports', function () {
    $reportType = request('type', 'daily');
    $selectedCategory = request('category', 'all');
    $selectedPayment = request('payment', 'all');
    $dateFrom = request('date_from', '2026-03-01');
    $dateTo = request('date_to', '2026-03-04');

    $categories = ['all' => 'All Categories', 'electronics' => 'Electronics', 'accessories' => 'Accessories', 'gadgets' => 'Gadgets'];
    $paymentMethods = ['all' => 'All Methods', 'credit_card' => 'Credit Card', 'debit_card' => 'Debit Card', 'transfer' => 'Bank Transfer', 'cash' => 'Cash'];

    // FR-L01: Daily Sales Report with Hourly Breakdown
    $dailyTransactions = [
        ['hour' => '08:00 - 09:00', 'transactions' => 8, 'revenue' => 320, 'avg_value' => 40],
        ['hour' => '09:00 - 10:00', 'transactions' => 12, 'revenue' => 540, 'avg_value' => 45],
        ['hour' => '10:00 - 11:00', 'transactions' => 15, 'revenue' => 735, 'avg_value' => 49],
        ['hour' => '11:00 - 12:00', 'transactions' => 22, 'revenue' => 1210, 'avg_value' => 55],
        ['hour' => '12:00 - 13:00', 'transactions' => 25, 'revenue' => 1500, 'avg_value' => 60],
        ['hour' => '13:00 - 14:00', 'transactions' => 18, 'revenue' => 1080, 'avg_value' => 60],
        ['hour' => '14:00 - 15:00', 'transactions' => 20, 'revenue' => 1040, 'avg_value' => 52],
        ['hour' => '15:00 - 16:00', 'transactions' => 28, 'revenue' => 1568, 'avg_value' => 56],
        ['hour' => '16:00 - 17:00', 'transactions' => 32, 'revenue' => 1920, 'avg_value' => 60],
        ['hour' => '17:00 - 18:00', 'transactions' => 35, 'revenue' => 2275, 'avg_value' => 65],
        ['hour' => '18:00 - 19:00', 'transactions' => 40, 'revenue' => 2800, 'avg_value' => 70],
        ['hour' => '19:00 - 20:00', 'transactions' => 38, 'revenue' => 2660, 'avg_value' => 70],
    ];

    // FR-L02: Weekly Sales Report with Previous Week Comparison
    $weeklyData = [
        ['week' => 'Week 1', 'revenue' => 8500, 'transactions' => 145, 'prev_revenue' => 8200, 'growth' => 3.7],
        ['week' => 'Week 2', 'revenue' => 9500, 'transactions' => 168, 'prev_revenue' => 8800, 'growth' => 8.0],
        ['week' => 'Week 3', 'revenue' => 7800, 'transactions' => 132, 'prev_revenue' => 9100, 'growth' => -14.3],
        ['week' => 'Week 4', 'revenue' => 10200, 'transactions' => 185, 'prev_revenue' => 8500, 'growth' => 20.0],
    ];

    // FR-L03: Monthly Sales Report
    $monthlyData = [
        ['month' => 'January', 'revenue' => 25000, 'transactions' => 425, 'top_product' => 'Premium Widget Pro', 'top_product_qty' => 95],
        ['month' => 'February', 'revenue' => 28000, 'transactions' => 475, 'top_product' => 'Standard Widget', 'top_product_qty' => 105],
        ['month' => 'March', 'revenue' => 22500, 'transactions' => 380, 'top_product' => 'Deluxe Gadget Plus', 'top_product_qty' => 78],
    ];

    // FR-L04: Filtered Transactions (with category & payment method filter)
    $filteredTransactions = [
        ['date' => '2026-03-04', 'time' => '18:45', 'product' => 'Premium Widget Pro', 'category' => 'electronics', 'quantity' => 2, 'amount' => 120, 'payment' => 'credit_card'],
        ['date' => '2026-03-04', 'time' => '18:30', 'product' => 'Standard Widget', 'category' => 'electronics', 'quantity' => 3, 'amount' => 90, 'payment' => 'debit_card'],
        ['date' => '2026-03-04', 'time' => '18:15', 'product' => 'Advanced Package', 'category' => 'accessories', 'quantity' => 1, 'amount' => 50, 'payment' => 'credit_card'],
        ['date' => '2026-03-04', 'time' => '18:00', 'product' => 'Basic Tool Kit', 'category' => 'gadgets', 'quantity' => 4, 'amount' => 160, 'payment' => 'transfer'],
        ['date' => '2026-03-04', 'time' => '17:45', 'product' => 'Deluxe Gadget Plus', 'category' => 'gadgets', 'quantity' => 1, 'amount' => 40, 'payment' => 'cash'],
        ['date' => '2026-03-04', 'time' => '17:30', 'product' => 'Premium Widget Pro', 'category' => 'electronics', 'quantity' => 1, 'amount' => 60, 'payment' => 'credit_card'],
        ['date' => '2026-03-04', 'time' => '17:15', 'product' => 'Standard Widget', 'category' => 'electronics', 'quantity' => 2, 'amount' => 60, 'payment' => 'transfer'],
        ['date' => '2026-03-04', 'time' => '17:00', 'product' => 'Advanced Package', 'category' => 'accessories', 'quantity' => 3, 'amount' => 150, 'payment' => 'debit_card'],
    ];

    // Apply filters if needed
    if ($selectedCategory !== 'all') {
        $filteredTransactions = array_filter($filteredTransactions, fn($t) => $t['category'] === $selectedCategory);
    }
    if ($selectedPayment !== 'all') {
        $filteredTransactions = array_filter($filteredTransactions, fn($t) => $t['payment'] === $selectedPayment);
    }

    return view('admin.reports', compact(
        'reportType',
        'selectedCategory',
        'selectedPayment',
        'dateFrom',
        'dateTo',
        'categories',
        'paymentMethods',
        'dailyTransactions',
        'weeklyData',
        'monthlyData',
        'filteredTransactions'
    ));
});

// FR-L05: Export to Excel
Route::get('/dev/reports/export/excel', function () {
    $reportType = request('type', 'daily');
    $fileName = 'sales_report_' . $reportType . '_' . date('Y-m-d_His') . '.csv';

    // Generate CSV data
    $csv = "Sales Report - " . ucfirst($reportType) . " (" . date('Y-m-d H:i:s') . ")\n\n";

    if ($reportType === 'daily') {
        $csv .= "Time Period,Transactions,Revenue,Avg Transaction Value,Status\n";
        $dailyTransactions = [
            ['hour' => '08:00 - 09:00', 'transactions' => 8, 'revenue' => 320, 'avg_value' => 40],
            ['hour' => '09:00 - 10:00', 'transactions' => 12, 'revenue' => 540, 'avg_value' => 45],
            ['hour' => '10:00 - 11:00', 'transactions' => 15, 'revenue' => 735, 'avg_value' => 49],
            ['hour' => '11:00 - 12:00', 'transactions' => 22, 'revenue' => 1210, 'avg_value' => 55],
            ['hour' => '12:00 - 13:00', 'transactions' => 25, 'revenue' => 1500, 'avg_value' => 60],
            ['hour' => '13:00 - 14:00', 'transactions' => 18, 'revenue' => 1080, 'avg_value' => 60],
            ['hour' => '14:00 - 15:00', 'transactions' => 20, 'revenue' => 1040, 'avg_value' => 52],
            ['hour' => '15:00 - 16:00', 'transactions' => 28, 'revenue' => 1568, 'avg_value' => 56],
            ['hour' => '16:00 - 17:00', 'transactions' => 32, 'revenue' => 1920, 'avg_value' => 60],
            ['hour' => '17:00 - 18:00', 'transactions' => 35, 'revenue' => 2275, 'avg_value' => 65],
            ['hour' => '18:00 - 19:00', 'transactions' => 40, 'revenue' => 2800, 'avg_value' => 70],
            ['hour' => '19:00 - 20:00', 'transactions' => 38, 'revenue' => 2660, 'avg_value' => 70],
        ];
        foreach ($dailyTransactions as $item) {
            $status = $item['transactions'] >= 30 ? 'Peak' : 'Normal';
            $csv .= "\"{$item['hour']}\",{$item['transactions']},{$item['revenue']},{$item['avg_value']},\"{$status}\"\n";
        }
    } elseif ($reportType === 'weekly') {
        $csv .= "Week,Revenue,Transactions,Previous Week,Growth %\n";
        $weeklyData = [
            ['week' => 'Week 1', 'revenue' => 8500, 'transactions' => 145, 'prev_revenue' => 8200, 'growth' => 3.7],
            ['week' => 'Week 2', 'revenue' => 9500, 'transactions' => 168, 'prev_revenue' => 8800, 'growth' => 8.0],
            ['week' => 'Week 3', 'revenue' => 7800, 'transactions' => 132, 'prev_revenue' => 9100, 'growth' => -14.3],
            ['week' => 'Week 4', 'revenue' => 10200, 'transactions' => 185, 'prev_revenue' => 8500, 'growth' => 20.0],
        ];
        foreach ($weeklyData as $item) {
            $csv .= "\"{$item['week']}\",{$item['revenue']},{$item['transactions']},{$item['prev_revenue']},{$item['growth']}\n";
        }
    } else {
        $csv .= "Month,Revenue,Transactions,Avg Value,Top Product,Units Sold\n";
        $monthlyData = [
            ['month' => 'January', 'revenue' => 25000, 'transactions' => 425, 'top_product' => 'Premium Widget Pro', 'top_product_qty' => 95],
            ['month' => 'February', 'revenue' => 28000, 'transactions' => 475, 'top_product' => 'Standard Widget', 'top_product_qty' => 105],
            ['month' => 'March', 'revenue' => 22500, 'transactions' => 380, 'top_product' => 'Deluxe Gadget Plus', 'top_product_qty' => 78],
        ];
        foreach ($monthlyData as $item) {
            $avg = $item['revenue'] / $item['transactions'];
            $csv .= "\"{$item['month']}\",{$item['revenue']},{$item['transactions']},{$avg},\"{$item['top_product']}\",{$item['top_product_qty']}\n";
        }
    }

    return response()->streamDownload(
        function () use ($csv) {
            echo $csv;
        },
        $fileName,
        ['Content-Type' => 'text/csv']
    );
});

// FR-L06: Export to PDF
Route::get('/dev/reports/export/pdf', function () {
    $reportType = request('type', 'daily');
    $fileName = 'sales_report_' . $reportType . '_' . date('Y-m-d_His') . '.txt';

    // For simplicity, generate a text version (in production, use a PDF library like mPDF or DomPDF)
    $pdf = "╔═══════════════════════════════════════════════════════════╗\n";
    $pdf .= "║          INVENTORY360 - SALES REPORT - " . strtoupper($reportType) . "           ║\n";
    $pdf .= "║          Generated: " . date('Y-m-d H:i:s') . "                             ║\n";
    $pdf .= "╚═══════════════════════════════════════════════════════════╝\n\n";

    if ($reportType === 'daily') {
        $pdf .= "DAILY SALES SUMMARY (Hourly Breakdown)\n";
        $pdf .= "═══════════════════════════════════════════════════════════\n";
        $pdf .= "Total Revenue: \$18,828.00\n";
        $pdf .= "Total Transactions: 285\n";
        $pdf .= "Average Transaction Value: \$66.06\n\n";
        $pdf .= "Peak Hours: 18:00-20:00 (73 transactions)\n";
        $pdf .= "Slow Hours: 08:00-09:00 (8 transactions)\n\n";
    } elseif ($reportType === 'weekly') {
        $pdf .= "WEEKLY SALES SUMMARY (Trend Comparison)\n";
        $pdf .= "═══════════════════════════════════════════════════════════\n";
        $pdf .= "Total Revenue (4 weeks): \$36,000.00\n";
        $pdf .= "Best Week: Week 4 (\$10,200 - Growth +20.0%)\n";
        $pdf .= "Weakest Week: Week 3 (\$7,800 - Growth -14.3%)\n\n";
    } else {
        $pdf .= "MONTHLY SALES SUMMARY (Performance Analysis)\n";
        $pdf .= "═══════════════════════════════════════════════════════════\n";
        $pdf .= "Total Revenue (3 months): \$75,500.00\n";
        $pdf .= "Best Month: February (\$28,000)\n";
        $pdf .= "Best Product: Premium Widget Pro (298 units)\n\n";
    }

    $pdf .= "╔═══════════════════════════════════════════════════════════╗\n";
    $pdf .= "║           Report prepared for management review            ║\n";
    $pdf .= "║              For confidential use only                     ║\n";
    $pdf .= "╚═══════════════════════════════════════════════════════════╝\n";

    return response()->streamDownload(
        function () use ($pdf) {
            echo $pdf;
        },
        $fileName,
        ['Content-Type' => 'text/plain']
    );
});

// FR-L07: Scheduled Reports
Route::get('/dev/reports/scheduled', function () {
    // Hardcoded scheduled reports
    $scheduledReports = [
        [
            'id' => 1,
            'name' => 'Weekly Sales Summary',
            'type' => 'weekly',
            'frequency' => 'Every Monday at 09:00 AM',
            'recipients' => ['admin@inventory360.com', 'manager@inventory360.com'],
            'last_sent' => '2026-03-02 09:15:00',
            'next_send' => '2026-03-10 09:00:00',
            'active' => true,
        ],
        [
            'id' => 2,
            'name' => 'Monthly Performance Report',
            'type' => 'monthly',
            'frequency' => 'First day of month at 08:00 AM',
            'recipients' => ['director@inventory360.com'],
            'last_sent' => '2026-03-01 08:30:00',
            'next_send' => '2026-04-01 08:00:00',
            'active' => true,
        ],
        [
            'id' => 3,
            'name' => 'Daily Quick Report',
            'type' => 'daily',
            'frequency' => 'Every day at 06:00 PM',
            'recipients' => ['admin@inventory360.com'],
            'last_sent' => '2026-03-04 18:05:00',
            'next_send' => '2026-03-05 18:00:00',
            'active' => false,
        ],
    ];

    return view('admin.scheduled-reports', compact('scheduledReports'));
});

// Menu Management Module
Route::get('/dev/menu', function () {
    // FR-M01 & FR-M03: Menu Items with pricing and availability
    $menuItems = [
        ['id' => 1, 'name' => 'Nasi Kuning Spesial', 'category' => 'Rice Dishes', 'price' => 15.99, 'description' => 'Fragrant yellow rice with aromatic spices', 'available' => true, 'image' => 'nasi_kuning.jpg', 'stock' => 45],
        ['id' => 2, 'name' => 'Soto Ayam Tradisional', 'category' => 'Soups', 'price' => 8.99, 'description' => 'Traditional chicken soup with turmeric', 'available' => true, 'image' => 'soto_ayam.jpg', 'stock' => 60],
        ['id' => 3, 'name' => 'Gado-Gado Premium', 'category' => 'Salads', 'price' => 9.99, 'description' => 'Mixed vegetables with peanut sauce', 'available' => true, 'image' => 'gado_gado.jpg', 'stock' => 30],
        ['id' => 4, 'name' => 'Rendang Daging', 'category' => 'Main Dishes', 'price' => 18.99, 'description' => 'Slow-cooked beef in coconut curry', 'available' => true, 'image' => 'rendang.jpg', 'stock' => 25],
        ['id' => 5, 'name' => 'Lumpia Goreng', 'category' => 'Appetizers', 'price' => 6.99, 'description' => 'Fried spring rolls with sweet sauce', 'available' => false, 'image' => 'lumpia.jpg', 'stock' => 0],
        ['id' => 6, 'name' => 'Es Cendol Kelapa', 'category' => 'Beverages', 'price' => 4.99, 'description' => 'Iced coconut dessert drink', 'available' => true, 'image' => 'es_cendol.jpg', 'stock' => 50],
    ];

    $categories = [
        ['id' => 1, 'name' => 'Rice Dishes', 'description' => 'Traditional rice-based meals'],
        ['id' => 2, 'name' => 'Soups', 'description' => 'Hot and flavorful soups'],
        ['id' => 3, 'name' => 'Salads', 'description' => 'Fresh vegetable salads'],
        ['id' => 4, 'name' => 'Main Dishes', 'description' => 'Hearty main courses'],
        ['id' => 5, 'name' => 'Appetizers', 'description' => 'Starters and snacks'],
        ['id' => 6, 'name' => 'Beverages', 'description' => 'Drinks and beverages'],
    ];

    return view('admin.menu.index', compact('menuItems', 'categories'));
});

// FR-M01: Menu Item Create/Edit Form
Route::get('/dev/menu/{id?}', function ($id = null) {
    $isEdit = $id !== null;

    // FR-M04: Recipe Management
    $recipes = [
        ['ingredient' => 'Rice', 'unit' => 'kg', 'quantity' => 2.0, 'cost' => 2.50],
        ['ingredient' => 'Turmeric', 'unit' => 'g', 'quantity' => 50, 'cost' => 0.50],
        ['ingredient' => 'Coconut Milk', 'unit' => 'liter', 'quantity' => 1.5, 'cost' => 3.00],
        ['ingredient' => 'Salt', 'unit' => 'g', 'quantity' => 20, 'cost' => 0.10],
    ];

    $menuItem = $isEdit ? [
        'id' => $id,
        'name' => 'Nasi Kuning Spesial',
        'category' => 'Rice Dishes',
        'price' => 15.99,
        'cost' => 6.10,
        'description' => 'Fragrant yellow rice with aromatic spices',
        'available' => true,
        'image' => 'nasi_kuning.jpg',
        'stock' => 45,
    ] : null;

    $categories = [
        'Rice Dishes',
        'Soups',
        'Salads',
        'Main Dishes',
        'Appetizers',
        'Beverages'
    ];

    return view('admin.menu.form', compact('isEdit', 'menuItem', 'recipes', 'categories'));
})->where('id', '[0-9]+');

// FR-M02: Category Management
Route::get('/dev/menu/categories/manage', function () {
    $categories = [
        ['id' => 1, 'name' => 'Rice Dishes', 'description' => 'Traditional rice-based meals', 'items_count' => 5, 'created' => '2026-01-15'],
        ['id' => 2, 'name' => 'Soups', 'description' => 'Hot and flavorful soups', 'items_count' => 8, 'created' => '2026-01-15'],
        ['id' => 3, 'name' => 'Salads', 'description' => 'Fresh vegetable salads', 'items_count' => 4, 'created' => '2026-01-20'],
        ['id' => 4, 'name' => 'Main Dishes', 'description' => 'Hearty main courses', 'items_count' => 12, 'created' => '2026-01-15'],
        ['id' => 5, 'name' => 'Appetizers', 'description' => 'Starters and snacks', 'items_count' => 9, 'created' => '2026-02-01'],
        ['id' => 6, 'name' => 'Beverages', 'description' => 'Drinks and beverages', 'items_count' => 6, 'created' => '2026-02-05'],
    ];

    return view('admin.menu.categories', compact('categories'));
});

// FR-M05: Bulk Update Prices/Availability
Route::get('/dev/menu/bulk-update', function () {
    $menuItems = [
        ['id' => 1, 'name' => 'Nasi Kuning Spesial', 'category' => 'Rice Dishes', 'price' => 15.99, 'available' => true],
        ['id' => 2, 'name' => 'Soto Ayam Tradisional', 'category' => 'Soups', 'price' => 8.99, 'available' => true],
        ['id' => 3, 'name' => 'Gado-Gado Premium', 'category' => 'Salads', 'price' => 9.99, 'available' => true],
        ['id' => 4, 'name' => 'Rendang Daging', 'category' => 'Main Dishes', 'price' => 18.99, 'available' => true],
        ['id' => 5, 'name' => 'Lumpia Goreng', 'category' => 'Appetizers', 'price' => 6.99, 'available' => false],
        ['id' => 6, 'name' => 'Es Cendol Kelapa', 'category' => 'Beverages', 'price' => 4.99, 'available' => true],
    ];

    $categories = ['All', 'Rice Dishes', 'Soups', 'Salads', 'Main Dishes', 'Appetizers', 'Beverages'];

    return view('admin.menu.bulk-update', compact('menuItems', 'categories'));
});

// Inventory Management Module
Route::get('/dev/inventory', function () {
    $tab = request('tab', 'overview');

    // FR-I01: Raw Materials
    $rawMaterials = [
        ['id' => 1, 'name' => 'Rice (Long Grain)', 'unit' => 'kg', 'quantity' => 150, 'min_level' => 50, 'supplier' => 'Farm Fresh Co.', 'received' => '2026-03-02', 'unit_cost' => 1.25, 'status' => 'ok'],
        ['id' => 2, 'name' => 'Coconut Milk', 'unit' => 'liter', 'quantity' => 45, 'min_level' => 20, 'supplier' => 'Tropical Supplies', 'received' => '2026-03-01', 'unit_cost' => 3.50, 'status' => 'ok'],
        ['id' => 3, 'name' => 'Turmeric Powder', 'unit' => 'kg', 'quantity' => 8, 'min_level' => 5, 'supplier' => 'Spice Master', 'received' => '2026-02-28', 'unit_cost' => 8.00, 'status' => 'warning'],
        ['id' => 4, 'name' => 'Chicken Breast', 'unit' => 'kg', 'quantity' => 35, 'min_level' => 20, 'supplier' => 'Premium Poultry', 'received' => '2026-03-03', 'unit_cost' => 6.50, 'status' => 'ok'],
        ['id' => 5, 'name' => 'Beef (Diced)', 'unit' => 'kg', 'quantity' => 12, 'min_level' => 15, 'supplier' => 'Premium Meats', 'received' => '2026-03-02', 'unit_cost' => 12.00, 'status' => 'danger'],
        ['id' => 6, 'name' => 'Shallots', 'unit' => 'kg', 'quantity' => 25, 'min_level' => 10, 'supplier' => 'Local Market', 'received' => '2026-03-04', 'unit_cost' => 2.50, 'status' => 'ok'],
    ];

    // FR-I02: Finished Goods
    $finishedGoods = [
        ['id' => 1, 'name' => 'Nasi Kuning Spesial', 'unit' => 'portion', 'quantity' => 45, 'min_level' => 20, 'cost' => 6.10, 'price' => 15.99],
        ['id' => 2, 'name' => 'Soto Ayam Tradisional', 'unit' => 'portion', 'quantity' => 60, 'min_level' => 30, 'cost' => 3.50, 'price' => 8.99],
        ['id' => 3, 'name' => 'Gado-Gado Premium', 'unit' => 'portion', 'quantity' => 30, 'min_level' => 15, 'cost' => 4.20, 'price' => 9.99],
        ['id' => 4, 'name' => 'Rendang Daging', 'unit' => 'portion', 'quantity' => 25, 'min_level' => 10, 'cost' => 9.50, 'price' => 18.99],
        ['id' => 5, 'name' => 'Es Cendol Kelapa', 'unit' => 'glass', 'quantity' => 50, 'min_level' => 25, 'cost' => 1.80, 'price' => 4.99],
    ];

    // FR-I03 & FR-I04: Stock Alerts & History
    $alerts = [
        ['id' => 1, 'type' => 'low_stock', 'item' => 'Beef (Diced)', 'current' => 12, 'min_level' => 15, 'severity' => 'danger', 'created' => '2026-03-04 14:30:00'],
        ['id' => 2, 'type' => 'low_stock', 'item' => 'Turmeric Powder', 'current' => 8, 'min_level' => 5, 'severity' => 'warning', 'created' => '2026-03-04 12:15:00'],
        ['id' => 3, 'type' => 'expiring_soon', 'item' => 'Shallots', 'expires' => '2026-03-08', 'severity' => 'warning', 'created' => '2026-03-02 08:00:00'],
        ['id' => 4, 'type' => 'stock_adjustment', 'item' => 'Rice (Long Grain)', 'adjustment' => '+50 kg', 'severity' => 'info', 'created' => '2026-03-01 10:00:00'],
    ];

    $history = [
        ['id' => 1, 'date' => '2026-03-04 15:45:00', 'item' => 'Nasi Kuning Spesial', 'type' => 'out', 'quantity' => 5, 'user' => 'Admin', 'reason' => 'Sales Order #1245'],
        ['id' => 2, 'date' => '2026-03-04 14:30:00', 'item' => 'Beef (Diced)', 'type' => 'in', 'quantity' => 10, 'user' => 'Supplier', 'reason' => 'Partial delivery from Premium Meats'],
        ['id' => 3, 'date' => '2026-03-04 12:15:00', 'item' => 'Chicken Breast', 'type' => 'out', 'quantity' => 8, 'user' => 'Chef', 'reason' => 'Production batch #456'],
        ['id' => 4, 'date' => '2026-03-04 10:00:00', 'item' => 'Coconut Milk', 'type' => 'in', 'quantity' => 20, 'user' => 'Admin', 'reason' => 'Reorder point replenishment'],
        ['id' => 5, 'date' => '2026-03-03 18:30:00', 'item' => 'Soto Ayam Tradisional', 'type' => 'out', 'quantity' => 12, 'user' => 'Admin', 'reason' => 'Sales Order #1240'],
    ];

    // FR-I05: Supplier Management
    $suppliers = [
        ['id' => 1, 'name' => 'Farm Fresh Co.', 'contact' => 'farm@freshco.com', 'phone' => '+62-812-345-6789', 'address' => '123 Farm Lane, Rural Area', 'lead_time_days' => 2, 'avg_delivery_rating' => 4.8, 'items' => 'Rice, Grains, Vegetables', 'last_order' => '2026-03-02', 'total_orders' => 45],
        ['id' => 2, 'name' => 'Tropical Supplies', 'contact' => 'info@tropical.com', 'phone' => '+62-812-987-6543', 'address' => '456 Tropical Ave', 'lead_time_days' => 3, 'avg_delivery_rating' => 4.5, 'items' => 'Coconut Milk, Tropical Fruits', 'last_order' => '2026-03-01', 'total_orders' => 32],
        ['id' => 3, 'name' => 'Spice Master', 'contact' => 'sales@spicemaster.com', 'phone' => '+62-821-555-1234', 'address' => '789 Spice District', 'lead_time_days' => 4, 'avg_delivery_rating' => 4.3, 'items' => 'Spices, Seasonings', 'last_order' => '2026-02-28', 'total_orders' => 28],
        ['id' => 4, 'name' => 'Premium Poultry', 'contact' => 'orders@premiumpoultry.com', 'phone' => '+62-812-999-5555', 'address' => '321 Meat Market', 'lead_time_days' => 1, 'avg_delivery_rating' => 4.9, 'items' => 'Chicken, Poultry Products', 'last_order' => '2026-03-03', 'total_orders' => 52],
        ['id' => 5, 'name' => 'Premium Meats', 'contact' => 'sales@premiummeat.com', 'phone' => '+62-811-777-2222', 'address' => '654 Butcher Lane', 'lead_time_days' => 2, 'avg_delivery_rating' => 4.6, 'items' => 'Beef, Meat Products', 'last_order' => '2026-03-02', 'total_orders' => 38],
    ];

    // FR-I06: Stock Opname (Physical Count)
    $opnameItems = [
        ['id' => 1, 'name' => 'Rice (Long Grain)', 'unit' => 'kg', 'system_qty' => 150, 'physical_qty' => null, 'variance' => null],
        ['id' => 2, 'name' => 'Coconut Milk', 'unit' => 'liter', 'system_qty' => 45, 'physical_qty' => 44, 'variance' => -1],
        ['id' => 3, 'name' => 'Turmeric Powder', 'unit' => 'kg', 'system_qty' => 8, 'physical_qty' => 8, 'variance' => 0],
        ['id' => 4, 'name' => 'Chicken Breast', 'unit' => 'kg', 'system_qty' => 35, 'physical_qty' => 36, 'variance' => 1],
        ['id' => 5, 'name' => 'Beef (Diced)', 'unit' => 'kg', 'system_qty' => 12, 'physical_qty' => 11, 'variance' => -1],
    ];

    // FR-I07: Reorder Point Calculation
    $reorderAnalysis = [
        ['item' => 'Rice (Long Grain)', 'avg_daily_usage' => 7.5, 'supplier_lead_time' => 2, 'calculated_reorder' => 15, 'current_setting' => 50, 'recommendation' => 'Reduce to 15kg', 'efficiency' => 'high'],
        ['item' => 'Coconut Milk', 'avg_daily_usage' => 1.5, 'supplier_lead_time' => 3, 'calculated_reorder' => 4.5, 'current_setting' => 20, 'recommendation' => 'Reduce to 5L', 'efficiency' => 'medium'],
        ['item' => 'Chicken Breast', 'avg_daily_usage' => 2.0, 'supplier_lead_time' => 1, 'calculated_reorder' => 2.0, 'current_setting' => 20, 'recommendation' => 'Reduce to 3kg', 'efficiency' => 'low'],
        ['item' => 'Beef (Diced)', 'avg_daily_usage' => 0.5, 'supplier_lead_time' => 2, 'calculated_reorder' => 1.0, 'current_setting' => 15, 'recommendation' => 'Reduce to 2kg', 'efficiency' => 'low'],
        ['item' => 'Turmeric Powder', 'avg_daily_usage' => 0.1, 'supplier_lead_time' => 4, 'calculated_reorder' => 0.4, 'current_setting' => 5, 'recommendation' => 'No change needed', 'efficiency' => 'medium'],
    ];

    return view('admin.inventory.inventory', compact('tab', 'rawMaterials', 'finishedGoods', 'alerts', 'history', 'suppliers', 'opnameItems', 'reorderAnalysis'));
});





Route::middleware(['guest'])->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Master routes
Route::prefix('/dev/master')->group(function () {
    Route::get('/hak-akses', function () {
        return view('admin.master.hak-akses');
    })->name('admin.master.hak-akses');
});

Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');


});