<?php

namespace Database\Seeders;

use App\Models\AnalysisModel;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\IOFactory;

class AnalysisModelSeeder extends Seeder
{
    public function run(): void
    {
        $sourcePath = 'C:/Users/hassan/Downloads/نماذج_التحليل_الوظيفي_والكفاءات.xlsx';
        $originalFileName = 'نماذج_التحليل_الوظيفي_والكفاءات.xlsx';

        // Copy file to storage
        $storagePath = storage_path('app/analysis-models');
        if (!is_dir($storagePath)) {
            mkdir($storagePath, 0755, true);
        }

        $destFileName = time() . '_' . $originalFileName;
        $destPath = $storagePath . '/' . $destFileName;
        copy($sourcePath, $destPath);
        $relativePath = 'analysis-models/' . $destFileName;

        // Parse the Excel file
        $excelData = $this->parseExcelFile($destPath);

        // Generate unique slug
        $name = 'نماذج التحليل الوظيفي والكفاءات';
        $slug = 'analysis-models-forms';
        $counter = 1;
        while (AnalysisModel::where('slug', $slug)->exists()) {
            $slug = 'analysis-models-forms-' . $counter++;
        }

        AnalysisModel::create([
            'name'               => $name,
            'slug'               => $slug,
            'description'        => 'نماذج شاملة للتحليل الوظيفي وتقييم الكفاءات الأساسية وتحليل الأعمال — مكونة من أربعة نماذج متكاملة',
            'icon'               => 'fa-briefcase',
            'color'              => '#D4AF37',
            'original_file_name' => $originalFileName,
            'file_path'          => $relativePath,
            'structure'          => $excelData['structure'],
            'data'               => $excelData['data'],
            'is_active'          => true,
            'is_featured'        => true,
            'order'              => 1,
        ]);

        $this->command->info('✅ تم إنشاء النماذج بنجاح — ' . $name);
    }

    // ─── Excel Parsing (mirrors Admin\AnalysisModelController) ───────────────

    private function parseExcelFile(string $filePath): array
    {
        $spreadsheet = IOFactory::load($filePath);
        $sheetCount  = $spreadsheet->getSheetCount();

        $allSheets = [];

        for ($s = 0; $s < $sheetCount; $s++) {
            $sheet       = $spreadsheet->getSheet($s);
            $sheetTitle  = $sheet->getTitle();
            $highestRow  = $sheet->getHighestRow();
            $highestCol  = $sheet->getHighestColumn();
            $colIndex    = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestCol);

            $sheetData = [];
            for ($row = 1; $row <= $highestRow; $row++) {
                $rowData  = [];
                $hasData  = false;
                for ($col = 1; $col <= $colIndex; $col++) {
                    $cell  = $sheet->getCellByColumnAndRow($col, $row);
                    $value = $cell->getValue();

                    if ($value instanceof \PhpOffice\PhpSpreadsheet\RichText\RichText) {
                        $value = $value->getPlainText();
                    }
                    if ($cell->isFormula()) {
                        $value = $cell->getCalculatedValue();
                    }

                    $rowData[] = $value;
                    if (!empty($value)) {
                        $hasData = true;
                    }
                }
                if ($hasData) {
                    $sheetData[] = ['row' => $row, 'cells' => $rowData];
                }
            }

            $mergedCells = [];
            foreach ($sheet->getMergeCells() as $mergeRange) {
                $mergedCells[] = $mergeRange;
            }

            $allSheets[] = [
                'title'        => $sheetTitle,
                'slug'         => Str::slug($sheetTitle),
                'column_count' => $colIndex,
                'merged_cells' => $mergedCells,
                'sections'     => $this->parseSheetStructure($sheetData, $colIndex),
                'raw_data'     => $sheetData,
            ];
        }

        return [
            'structure' => ['sheet_count' => $sheetCount, 'form_type' => 'multi_sheet'],
            'data'      => ['sheets' => $allSheets],
        ];
    }

    private function parseSheetStructure(array $data, int $columnCount): array
    {
        $sections       = [];
        $currentSection = null;

        foreach ($data as $rowInfo) {
            $row          = $rowInfo['cells'];
            $rowNum       = $rowInfo['row'];
            $firstCell    = trim($row[0] ?? '');
            $nonEmpty     = array_filter($row, fn($v) => !empty(trim($v ?? '')));
            $nonEmptyCount = count($nonEmpty);

            if (empty($firstCell) && $nonEmptyCount === 0) {
                continue;
            }

            // Skip form title row
            if ($rowNum === 1 && !empty($firstCell)) {
                continue;
            }

            // Section header?
            if ($this->isSectionHeader($firstCell, $nonEmptyCount)) {
                if ($currentSection && !empty($currentSection['fields'])) {
                    $sections[] = $currentSection;
                }
                $currentSection = [
                    'title'         => $firstCell,
                    'type'          => 'section',
                    'fields'        => [],
                    'table_headers' => null,
                ];
                continue;
            }

            // Table header row?
            if ($nonEmptyCount >= 2 && $this->isTableHeaderRow($row)) {
                if ($currentSection) {
                    $currentSection['type']          = 'table';
                    $currentSection['table_headers'] = array_values(
                        array_filter($row, fn($v) => !empty(trim($v ?? '')))
                    );
                }
                continue;
            }

            // Add field / table row
            if ($currentSection) {
                if ($currentSection['type'] === 'table' && $currentSection['table_headers']) {
                    $currentSection['fields'][] = ['type' => 'table_row', 'cells' => $row];
                } else {
                    $options = $this->extractOptions($row[1] ?? '');
                    $field   = [
                        'label'       => $firstCell,
                        'type'        => $this->detectFieldType($firstCell, $row[1] ?? ''),
                        'placeholder' => $row[1] ?? '',
                        'options'     => $options,
                        'columns'     => [],
                    ];
                    for ($i = 1; $i < $columnCount; $i++) {
                        if (!empty(trim($row[$i] ?? ''))) {
                            $field['columns'][] = [
                                'label' => $row[$i],
                                'type'  => $this->detectFieldType('', $row[$i] ?? ''),
                            ];
                        }
                    }
                    $currentSection['fields'][] = $field;
                }
            } else {
                $currentSection = [
                    'title'         => 'البيانات الأساسية',
                    'type'          => 'section',
                    'fields'        => [],
                    'table_headers' => null,
                ];
                if (!empty($firstCell) && $firstCell !== 'البيان') {
                    $currentSection['fields'][] = [
                        'label'       => $firstCell,
                        'type'        => $this->detectFieldType($firstCell, $row[1] ?? ''),
                        'placeholder' => $row[1] ?? '',
                        'options'     => $this->extractOptions($row[1] ?? ''),
                        'columns'     => [],
                    ];
                }
            }
        }

        if ($currentSection && !empty($currentSection['fields'])) {
            $sections[] = $currentSection;
        }

        return $sections;
    }

    private function isSectionHeader(string $firstCell, int $nonEmptyCount): bool
    {
        if ($nonEmptyCount > 2) {
            return false;
        }

        $patterns = [
            'المهام', 'المسؤوليات', 'المهارات', 'المؤهلات', 'الخبرات', 'المتطلبات',
            'الصلاحيات', 'العلاقات', 'بيئة العمل', 'الكفاءات', 'الأهداف', 'معلومات',
            'البيانات', 'تحليل', 'المخاطر', 'الفرص', 'ملخص', 'التوصيات', 'معايير',
            'التقييم', 'التطوير', 'نقاط القوة', 'مجالات',
        ];

        foreach ($patterns as $pattern) {
            if (str_contains($firstCell, $pattern)) {
                return true;
            }
        }

        return false;
    }

    private function isTableHeaderRow(array $row): bool
    {
        $keywords = [
            'المستوى', 'الأهمية', 'الوصف', 'التفاصيل', 'النسبة', 'الوقت',
            'الموارد', 'المؤشرات', 'الأولوية', 'الحد الأدنى', 'المفضل',
            'الملاحظات', 'الإجراءات',
        ];

        $matchCount = 0;
        foreach ($row as $cell) {
            $cell = trim($cell ?? '');
            foreach ($keywords as $kw) {
                if (str_contains($cell, $kw)) {
                    $matchCount++;
                    break;
                }
            }
        }

        return $matchCount >= 2;
    }

    private function extractOptions(string $text): array
    {
        $options = [];
        if (preg_match('/\[(.+)\]/', $text, $matches)) {
            $parts = preg_split('/[\/|]/', $matches[1]);
            foreach ($parts as $part) {
                $part = trim($part);
                if (!empty($part)) {
                    $options[] = $part;
                }
            }
        }
        return $options;
    }

    private function detectFieldType(string $label, string $placeholder): string
    {
        $label       = mb_strtolower($label);
        $placeholder = mb_strtolower($placeholder);

        if (str_contains($label, 'تاريخ') || str_contains($label, 'تحديث')) {
            return 'date';
        }
        if (str_contains($label, 'راتب') || str_contains($label, 'سنوات') || str_contains($label, 'رقم')) {
            return 'number';
        }
        if (str_contains($placeholder, '/') || str_contains($placeholder, '|')) {
            return 'select';
        }
        if (str_contains($label, 'وصف') || str_contains($label, 'مهمة') || str_contains($label, 'تفاصيل')) {
            return 'textarea';
        }

        return 'text';
    }
}
