<?php

namespace Package\XReport\ExcelReportService;

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
