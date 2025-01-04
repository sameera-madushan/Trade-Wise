<?php

namespace Package\XReport\Services;

class ReportContext
{
    protected $reportGenerator;

    public function setReportGenerator(ReportGeneratorInterface $reportGenerator): void
    {
        $this->reportGenerator = $reportGenerator;
    }

    public function generateReport(array $filters): \Illuminate\Support\Collection
    {
        return $this->reportGenerator->generate($filters);
    }
}
