<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CurrencyController extends Controller
{
    public function index()
    {
        $currencies = Currency::paginate(10);
        return view('admin.multinational.currencies.index', compact('currencies'));
    }

    public function create()
    {
        return view('admin.multinational.currencies.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'currency_code' => 'required|string|unique:currency,currency_code|max:3', 
            'name' => 'required|string|max:50', 
            'exchange_rate' => 'required|numeric|min:0',
            'symbol' => 'required|string|max:10',
            'format' => 'required|string|max:50'
        ]);
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        Currency::create([
            'currency_code' => $request->currency_code,
            'name' => $request->name,
            'exchange_rate' => $request->exchange_rate,
            'symbol' => $request->symbol,
            'format' => $request->format
        ]);

        return redirect()->route('admin.multinational.currencies')
            ->with('success', 'Currency đã được tạo thành công!');
    }

    public function edit($currency_code)
    {
        $currency = Currency::findOrFail($currency_code);
        if (!$currency) {
            return redirect()->route('admin.multinational.currencies')
                ->with('error', 'Currency không tồn tại!');
        }
        return view('admin.multinational.currencies.edit', compact('currency'));
    }

    public function update(Request $request, $currency_code)
    {
        $validator = Validator::make($request->all(), [
            'currency_code' => 'required|string|unique:currency,currency_code,' . $currency_code . ',currency_code|max:3',
            'name' => 'required|string|max:50',
            'exchange_rate' => 'required|numeric|min:0',
            'symbol' => 'required|string|max:10',
            'format' => 'required|string|max:50'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $currency = Currency::findOrFail($currency_code);
        $currency->update([
            'currency_code' => $request->currency_code,
            'name' => $request->name,
            'exchange_rate' => $request->exchange_rate,
            'symbol' => $request->symbol,
            'format' => $request->format
        ]);

        return redirect()->route('admin.multinational.currencies')
            ->with('success', 'Currency đã được cập nhật thành công!');
    }

    public function destroy($currency_code)
    {
        \Log::info('Deleting Currency with currency_code: ' . $currency_code);
        
        $currency = Currency::find($currency_code);
        
        if (!$currency) {
            return redirect()->route('admin.multinational.currencies')
                ->with('error', 'Currency không tồn tại!');
        }
        
        $currency->delete();
        
        \Log::info('Currency deleted successfully');
        
        return redirect()->route('admin.multinational.currencies')
            ->with('success', 'Currency đã được xóa thành công!');
    }
}