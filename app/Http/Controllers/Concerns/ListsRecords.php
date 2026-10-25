<?php

namespace App\Http\Controllers\Concerns;

use Illuminate\Http\Request;

trait ListsRecords
{
    protected function perPage(Request $request): int
    {
        return in_array((int) $request->get('per_page'), [10, 25, 50, 100], true)
            ? (int) $request->get('per_page')
            : 10;
    }

    /** ?edit=ID loads a record into the modal, ?add=1 opens an empty form. */
    protected function editing(Request $request, string $modelClass)
    {
        if ($request->filled('edit')) {
            return $modelClass::findOrFail($request->get('edit'));
        }

        return $request->has('add') ? new $modelClass : null;
    }
}
