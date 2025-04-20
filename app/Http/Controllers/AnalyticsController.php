<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class AnalyticsController extends Controller
{
    private array $data = [];
    public function index()
    {
        $currentYear = date('Y');

        $monthlyRevenue = array_fill(0, 12, 0);

        $revenueData = DB::table('user_courses')
            ->join('courses', 'user_courses.courses_id', '=', 'courses.id')
            ->whereYear('user_courses.created_at', $currentYear)
            ->where('courses.is_free', false)
            ->whereNotNull('courses.price')
            ->select(
                DB::raw('MONTH(user_courses.created_at) as month'),
                DB::raw('SUM(courses.price) as total_revenue')
            )
            ->groupBy('month')
            ->get();

        foreach ($revenueData as $data) {
            $monthlyRevenue[$data->month - 1] = (int) $data->total_revenue;
        }

        $totalRevenue = array_sum($monthlyRevenue);

        $monthlySalesCount = array_fill(0, 12, 0);
        $salesCountData = DB::table('user_courses')
            ->whereYear('created_at', $currentYear)
            ->select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('COUNT(*) as total_sales')
            )
            ->groupBy('month')
            ->get();

        foreach ($salesCountData as $data) {
            $monthlySalesCount[$data->month - 1] = $data->total_sales;
        }

        $totalSales = array_sum($monthlySalesCount);

        $courseDistribution = DB::table('courses')
            ->join('category_courses', 'courses.category_courses_id', '=', 'category_courses.id')
            ->select(
                'category_courses.name as category',
                DB::raw('COUNT(courses.id) as count')
            )
            ->groupBy('category_courses.id', 'category_courses.name')
            ->get();

        $categoryNames = $courseDistribution->pluck('category')->toArray();
        $categoryCounts = $courseDistribution->pluck('count')->toArray();
        $categories = DB::table('category_courses')
            ->select('id', 'name')
            ->where('status', 1)
            ->get();

        $topCoursesByCategory = [];

        $selectedCategoryId = request()->input('category_courses_id') ?? null;

        if($selectedCategoryId){
            $topCoursesByCategory = $this->getTopCoursesByCategory($selectedCategoryId);

        } else if (empty($selectedCategoryId)) {
            $selectedCategoryId = null;
        } else {
            // Otherwise, get top courses for each category
            foreach ($categories as $category) {
                $topCoursesByCategory[$category->id] = [
                    'category_name' => $category->name,
                    'courses' => $this->getTopCoursesByCategory($category->id)
                ];
            }
        }

        return Inertia::render('Analytics', [
            'chartData' => [
                'data' => $monthlyRevenue,
                'totalRevenue' => $totalRevenue,
                'totalSales' => $totalSales,
                'courseDistribution' => [
                    'labels' => $categoryNames,
                    'data' => $categoryCounts
                ]
            ],
            'categories' => $categories,
            'topCourses' => $topCoursesByCategory,
            'selectedCategoryId' => $selectedCategoryId
        ]);
    }
    private function getTopCoursesByCategory($categoryId)
    {
        return DB::table('courses')
            ->leftJoin('user_courses', 'courses.id', '=', 'user_courses.courses_id')
            ->where('courses.category_courses_id', $categoryId)
            ->where('status', 1)
            ->where('courses.is_free', false)
            ->select(
                'courses.id',
                'courses.title',
                'courses.price',
                DB::raw('COUNT(user_courses.id) as enrollment_count')
            )
            ->groupBy('courses.id', 'courses.title', 'courses.price')
            ->orderBy('enrollment_count', 'desc')
            ->limit(5)
            ->get();
    }
}
