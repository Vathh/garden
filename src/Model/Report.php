<?php

namespace App\Model;

use DateTime;

class Report
{
    private string $date;
    private ?string $pdfPath = null;
    private ?string $xlsxPath = null;

    public function __construct(string $date)
    {
        $this->date = $date;
    }

    public function getDate(): string
    {
        return $this->date;
    }

    public function setDate(string $date): Report
    {
        $this->date = $date;
        return $this;
    }

    public function getPdfPath(): ?string
    {
        return $this->pdfPath;
    }

    public function setPdfPath(?string $pdfPath): Report
    {
        $this->pdfPath = $pdfPath;
        return $this;
    }

    public function getXlsxPath(): ?string
    {
        return $this->xlsxPath;
    }

    public function setXlsxPath(?string $xlsxPath): Report
    {
        $this->xlsxPath = $xlsxPath;
        return $this;
    }

    public function hasPdf(): bool
    {
        return $this->pdfPath !== null;
    }

    public function hasXlsx(): bool
    {
        return $this->xlsxPath !== null;
    }

    public function getFormattedDate(): string
    {
        return DateTime::createFromFormat('Ymd', $this->date)->format('Y-m-d');
    }
}