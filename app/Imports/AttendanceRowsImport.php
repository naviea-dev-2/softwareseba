<?php

namespace App\Imports;

use App\Models\AttendanceImportRow;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\RemembersRowNumber;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class AttendanceRowsImport implements
    ToModel,
    WithHeadingRow,
    WithChunkReading
{
    use RemembersRowNumber;

    protected int $importId;

    public function __construct(int $importId)
    {
        $this->importId = $importId;
    }

    public function model(array $row)
    {
        // Ignore entirely blank rows.
        if (collect($row)->every(
            fn ($value) => $value === null || trim((string) $value) === ''
        )) {
            return null;
        }

        AttendanceImportRow::create([
            'attendance_import_id' => $this->importId,
            'row_number' => $this->getRowNumber(),
            'raw_data' => [
                'empID' => $row['empid'] ?? null,
                'dutyDate' => $row['dutydate'] ?? null,
                'shiftID' => $row['shiftid'] ?? null,
                'inTime' => $row['intime'] ?? null,
                'outTime' => $row['outtime'] ?? null,
            ],
        ]);

        return null;
    }

    public function chunkSize(): int
    {
        return 500;
    }
}