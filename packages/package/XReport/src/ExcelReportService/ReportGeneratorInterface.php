<?php

namespace Package\XReport\ExcelReportService;

interface ReportGeneratorInterface
{
    public function generate(array $filters): \Illuminate\Support\Collection;
}
