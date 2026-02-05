<?php

namespace App\DTOs;

class SessionReportDTO
{
    public function __construct(
        public $reports,
        public $session,
        public $settlements,
        public $settlementTotals,
        public $selectedType,
        public $attendedCount,
        public $showPhone,
        public $showParentPhone,
        public $showMaterials,
        public $showPrintables,
        public $computedReports,
        public $summaryData,
        public $totalsData,
    ) {}

    public static function create(array $data): self
    {
        return new self(...$data);
    }

    public function toArray(): array
    {
        return [
            'reports' => $this->reports,
            'session' => $this->session,
            'settlements' => $this->settlements,
            'settlementTotals' => $this->settlementTotals,
            'selectedType' => $this->selectedType,
            'attendedCount' => $this->attendedCount,
            'showPhone' => $this->showPhone,
            'showParentPhone' => $this->showParentPhone,
            'showMaterials' => $this->showMaterials,
            'showPrintables' => $this->showPrintables,
            'computedReports' => $this->computedReports,
            'summaryData' => $this->summaryData,
            'totalsData' => $this->totalsData,
        ];
    }
}
