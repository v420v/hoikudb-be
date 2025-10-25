<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PreschoolMonthlyStatResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'kind' => $this->kind,
            'target_date' => $this->target_date,
            'zero_year_old' => $this->zero_year_old,
            'one_year_old' => $this->one_year_old,
            'two_year_old' => $this->two_year_old,
            'three_year_old' => $this->three_year_old,
            'four_year_old' => $this->four_year_old,
            'five_year_old' => $this->five_year_old,
        ];
    }
}
