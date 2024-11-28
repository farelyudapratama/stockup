<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\SaleDetail;
use Illuminate\Http\Request;

class SalesReportController extends Controller
{
    public function index(Request $request)
    {
        // Fetch all products for the dropdown
        $products = Product::all();

        // Base query for sale details
        $query = SaleDetail::with('product');
        // dd($query->get());

        // Apply product filter
        if ($request->filled('product_id')) {
            $query->where('product_id', $request->input('product_id'));
        }

        // Apply date filter
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $startDate = $request->input('start_date');
            $endDate = $request->input('end_date');
        
            // Ensure end date is greater than or equal to start date
            if ($startDate > $endDate) {
                return redirect()->back()->withErrors(['end_date' => 'End date must be greater than or equal to start date']);
            }
        
            // Adjust end date to include the entire day
            $startDate = $startDate . ' 00:00:00'; // Start of the day
            $endDate = $endDate . ' 23:59:59';    // End of the day
        
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }
        

        // Debugging query
        // dd($query->toSql(), $query->getBindings());
        // dd(SaleDetail::where('id', 32)->get());

        // Fetch data and group by product_id
        $sales = $query->get()
            ->groupBy('product_id')
            ->map(function ($group) {
                $firstItem = $group->first();
                return [
                    'product_id' => $firstItem->product_id,
                    'product_name' => $firstItem->product->name,
                    'quantity' => $group->sum('quantity'),
                    'unit_price' => $firstItem->unit_price,
                    'total' => $group->sum('subtotal'),
                ];
            });


        // Recalculate totals
        $totalUnitsSold = $sales->sum('quantity');
        $totalTransactions = $query->pluck('sales_id')->unique()->count();
        $totalAmount = $sales->sum('total');

        return view('reports.sale', [
            'sales' => $sales,
            'products' => $products,
            'totalUnitsSold' => $totalUnitsSold,
            'totalTransactions' => $totalTransactions,
            'totalAmount' => $totalAmount,
        ]);
    }

    public function export(Request $request)
    {
        $filters = $request->only(['product_id', 'start_date', 'end_date']);

        return (new \App\Exports\SalesReportExport($filters))->download('sales-report.xlsx');
    }
}