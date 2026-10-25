<?php
namespace App\Http\Controllers\Hr;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\ListsRecords;
use App\Models\Hr\Setting\TaxSetting;
use Illuminate\Http\Request;

class TaxSettingController extends Controller
{
    use ListsRecords;

    public function index(Request $request)
    {
        $settings = TaxSetting::withCount('slabs')
            ->when($request->filled('q'), fn ($q) => $q->where('name', 'like', '%'.$request->q.'%'))
            ->orderByDesc('effective_from')
            ->paginate($this->perPage($request))
            ->withQueryString();

        return view('Hr.setting.tax-settings.index', [
            'settings' => $settings,
            'editing'  => $this->editing($request, TaxSetting::class),
        ]);
    }

    public function store(Request $request)
    {
        TaxSetting::create($this->validated($request));

        return redirect()->route('tax-settings.index')->with('status', 'Tax setting saved.');
    }

    public function update(Request $request, TaxSetting $taxSetting)
    {
        $taxSetting->update($this->validated($request));

        return redirect()->route('tax-settings.index')->with('status', 'Tax setting updated.');
    }

    public function destroy(TaxSetting $taxSetting)
    {
        $taxSetting->delete();

        return back()->with('status', 'Tax setting deleted.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'name'           => ['required', 'string', 'max:255'],
            'tax_type'       => ['required', 'in:Percentage,Fixed Amount,Slab'],
            'rate'           => ['required', 'numeric', 'min:0'],
            'threshold'      => ['nullable', 'numeric', 'min:0'],
            'effective_from' => ['required', 'date'],
            'status'         => ['required', 'in:Active,Inactive'],
        ]);
    }
}
