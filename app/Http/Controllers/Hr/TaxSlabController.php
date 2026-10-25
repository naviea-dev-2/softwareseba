<?php

namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;

use App\Models\Hr\Setting\TaxSetting;
use App\Models\Hr\Setting\TaxSlab;
use Illuminate\Http\Request;

class TaxSlabController extends Controller
{
    public function index(TaxSetting $taxSetting)
    {
        return view('Hr.setting.tax-slabs.index', [
            'setting' => $taxSetting,
            'slabs'   => $taxSetting->slabs()->orderBy('from_amount')->get(),
        ]);
    }

    public function store(Request $request, TaxSetting $taxSetting)
    {
        $data = $request->validate([
            'from_amount' => ['required', 'numeric', 'min:0'],
            'to_amount'   => ['nullable', 'numeric', 'gt:from_amount'],
            'rate'        => ['required', 'numeric', 'min:0'],
        ]);

        $taxSetting->slabs()->create($data);

        return redirect()->route('tax-slabs.index', $taxSetting)->with('status', 'Slab added.');
    }

    public function destroy(TaxSlab $taxSlab)
    {
        $setting = $taxSlab->taxSetting;
        $taxSlab->delete();

        return redirect()->route('tax-slabs.index', $setting)->with('status', 'Slab deleted.');
    }
}
