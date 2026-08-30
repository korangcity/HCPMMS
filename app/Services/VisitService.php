<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Visit;
use Illuminate\Support\Facades\DB;

final class VisitService
{
    public function create(
        array $data,
    ): Visit {
        return DB::transaction(
            function () use ($data): Visit {
                return Visit::query()->create($data);
            },
        );
    }

    public function update(
        Visit $visit,
        array $data,
    ): Visit {
        return DB::transaction(
            function () use (
                $visit,
                $data,
            ): Visit {
                $visit->update($data);

                return $visit->refresh();
            },
        );
    }

    public function delete(
        Visit $visit,
    ): void {
        DB::transaction(
            function () use ($visit): void {
                $visit->delete();
            },
        );
    }
}
