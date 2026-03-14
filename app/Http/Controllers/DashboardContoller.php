<?php

namespace App\Http\Controllers;

class DashboardController extends Controller
{
    public function index()
    {
        $salesOverviewData = [
            ['date' => '1 Apr', 'value' => 7200],
            ['date' => '5 Apr', 'value' => 7500],
            ['date' => '10 Apr', 'value' => 7300],
            ['date' => '15 Apr', 'value' => 7600],
            ['date' => '20 Apr', 'value' => 8100],
            ['date' => '25 Apr', 'value' => 8500],
            ['date' => '30 Apr', 'value' => 9800],
        ];

        $monthlySalesData = [
            ['date' => '1 Apr', 'value' => 1000],
            ['date' => '5 Apr', 'value' => 2500],
            ['date' => '10 Apr', 'value' => 4500],
            ['date' => '15 Apr', 'value' => 8200],
            ['date' => '20 Apr', 'value' => 9800],
            ['date' => '25 Apr', 'value' => 7000],
            ['date' => '30 Apr', 'value' => 6200],
        ];

        $periods = ['Today', 'This Week', 'This Month'];

        $headphonesImg = 'https://images.unsplash.com/photo-1631056471491-e605d42afd33?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxiZWF0cyUyMHN0dWRpbyUyMHBybyUyMGhlYWRwaG9uZXMlMjBwcm9kdWN0fGVufDF8fHx8MTc3MjYzNzgyN3ww&ixlib=rb-4.1.0&q=80&w=200';

        $productImages = [
            'https://images.unsplash.com/photo-1593640495253-23196b27a87f?w=60&h=60&fit=crop',
            'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=60&h=60&fit=crop',
            'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=60&h=60&fit=crop',
            'https://images.unsplash.com/photo-1491553895911-0055eca6402d?w=60&h=60&fit=crop',
            'https://images.unsplash.com/photo-1585386959984-a4155224a1ad?w=60&h=60&fit=crop',
            'https://images.unsplash.com/photo-1546435770-a3e426bf472b?w=60&h=60&fit=crop',
        ];

        return view('admin.dashboard', compact(
            'salesOverviewData',
            'monthlySalesData',
            'periods',
            'headphonesImg',
            'productImages'
        ));
    }
}