<?php

namespace Package\XReport\Services;

interface ReportGeneratorInterface
{
    public function generate(array $filters): \Illuminate\Support\Collection;
}
