<?php

namespace App\Traits;

use Illuminate\Http\Request;

trait BusinessProductionTrait
{
    protected function businessQuery($model, string $businessColumn = 'user_id')
    {
        return $model::where(
            $businessColumn,
            $this->businessId
        );
    }

    protected function findBusinessRecord(
        $model,
        $id,
        string $businessColumn = 'user_id'
    ) {
        return $this->businessQuery($model, $businessColumn)
            ->findOrFail($id);
    }

    protected function deleteBusinessRecord(
        $model,
        $id,
        string $businessColumn = 'user_id'
    ) {
        $record = $this->findBusinessRecord(
            $model,
            $id,
            $businessColumn
        );

        $record->delete();

        return $record;
    }

    protected function applyFilters(
        $query,
        Request $request,
        array $config = []
    ) {
        // Search
        if ($request->filled('search') && !empty($config['search'])) {
            $search = $request->search;

            $query->where(function ($q) use ($search, $config) {
                foreach ($config['search'] as $column) {
                    $q->orWhere(
                        $column,
                        'like',
                        "%{$search}%"
                    );
                }
            });
        }

        // Status
        if (
            $request->filled('status') &&
            ($config['status'] ?? false)
        ) {
            $query->where(
                'status',
                $request->status
            );
        }

        // Custom filters
        if (!empty($config['filters'])) {
            foreach ($config['filters'] as $requestKey => $column) {
                if ($request->filled($requestKey)) {
                    $query->where(
                        $column,
                        $request->{$requestKey}
                    );
                }
            }
        }

        return $query;
    }
}