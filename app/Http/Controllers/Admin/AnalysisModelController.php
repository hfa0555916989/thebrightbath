<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AnalysisModel;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\IOFactory;

class AnalysisModelController extends Controller
{
    /**
     * Display listing of all analysis models
     */
    public function index()
    {
        $models = AnalysisModel::ordered()->get();
        return view('admin.analysis-models.index', compact('models'));
    }

    /**
     * Show create form
     */
    public function create()
    {
        return view('admin.analysis-models.create');
    }

    /**
     * Store a new analysis model
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:50',
            'color' => 'nullable|string|max:20',
            'excel_file' => 'required|file|mimes:xlsx,xls,csv|max:10240',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
        ]);

        // Generate unique slug
        $slug = Str::slug($request->name);
        $originalSlug = $slug;
        $counter = 1;
        while (AnalysisModel::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter++;
        }

        // Process Excel file
        $file = $request->file('excel_file');
        $originalFileName = $file->getClientOriginalName();
        $path = $file->store('analysis-models');

        // Parse Excel structure and data
        $excelData = $this->parseExcelFile(storage_path('app/' . $path));

        // Create model
        $model = AnalysisModel::create([
            'name' => $request->name,
            'slug' => $slug,
            'description' => $request->description,
            'icon' => $request->icon ?? 'fa-file-alt',
            'color' => $request->color ?? '#D4AF37',
            'original_file_name' => $originalFileName,
            'file_path' => $path,
            'structure' => $excelData['structure'],
            'data' => $excelData['data'],
            'is_active' => $request->boolean('is_active', true),
            'is_featured' => $request->boolean('is_featured', false),
            'order' => AnalysisModel::max('order') + 1,
        ]);

        return redirect()
            ->route('admin.analysis-models.index')
            ->with('success', 'تم إنشاء النموذج "' . $model->name . '" بنجاح!');
    }

    /**
     * Show edit form
     */
    public function edit(AnalysisModel $analysisModel)
    {
        return view('admin.analysis-models.edit', ['model' => $analysisModel]);
    }

    /**
     * Update analysis model
     */
    public function update(Request $request, AnalysisModel $analysisModel)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:50',
            'color' => 'nullable|string|max:20',
            'excel_file' => 'nullable|file|mimes:xlsx,xls,csv|max:10240',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
        ]);

        $data = [
            'name' => $request->name,
            'description' => $request->description,
            'icon' => $request->icon ?? 'fa-file-alt',
            'color' => $request->color ?? '#D4AF37',
            'is_active' => $request->boolean('is_active', true),
            'is_featured' => $request->boolean('is_featured', false),
        ];

        // If new Excel file uploaded
        if ($request->hasFile('excel_file')) {
            // Delete old file
            if ($analysisModel->file_path && file_exists(storage_path('app/' . $analysisModel->file_path))) {
                unlink(storage_path('app/' . $analysisModel->file_path));
            }

            $file = $request->file('excel_file');
            $data['original_file_name'] = $file->getClientOriginalName();
            $data['file_path'] = $file->store('analysis-models');

            // Parse Excel
            $excelData = $this->parseExcelFile(storage_path('app/' . $data['file_path']));
            $data['structure'] = $excelData['structure'];
            $data['data'] = $excelData['data'];
        }

        $analysisModel->update($data);

        return redirect()
            ->route('admin.analysis-models.index')
            ->with('success', 'تم تحديث النموذج بنجاح!');
    }

    /**
     * Delete analysis model
     */
    public function destroy(AnalysisModel $analysisModel)
    {
        // Delete file
        if ($analysisModel->file_path && file_exists(storage_path('app/' . $analysisModel->file_path))) {
            unlink(storage_path('app/' . $analysisModel->file_path));
        }

        $name = $analysisModel->name;
        $analysisModel->delete();

        return redirect()
            ->route('admin.analysis-models.index')
            ->with('success', 'تم حذف النموذج "' . $name . '" بنجاح!');
    }

    /**
     * Reorder models
     */
    public function reorder(Request $request)
    {
        $request->validate([
            'order' => 'required|array',
            'order.*' => 'integer|exists:analysis_models,id',
        ]);

        foreach ($request->order as $index => $id) {
            AnalysisModel::where('id', $id)->update(['order' => $index]);
        }

        return response()->json(['success' => true]);
    }

    /**
     * Toggle active status
     */
    public function toggleActive(AnalysisModel $analysisModel)
    {
        $analysisModel->update(['is_active' => !$analysisModel->is_active]);

        return back()->with('success', 
            $analysisModel->is_active ? 'تم تفعيل النموذج' : 'تم إلغاء تفعيل النموذج'
        );
    }

    /**
     * Parse Excel file and extract structure + data from ALL sheets
     */
    private function parseExcelFile(string $filePath): array
    {
        $spreadsheet = IOFactory::load($filePath);
        $sheetCount = $spreadsheet->getSheetCount();
        
        $allSheets = [];
        
        for ($s = 0; $s < $sheetCount; $s++) {
            $sheet = $spreadsheet->getSheet($s);
            $sheetTitle = $sheet->getTitle();
            
            $highestRow = $sheet->getHighestRow();
            $highestColumn = $sheet->getHighestColumn();
            $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn);

            // Get all cells data with proper RichText handling
            $sheetData = [];
            for ($row = 1; $row <= $highestRow; $row++) {
                $rowData = [];
                $hasData = false;
                for ($col = 1; $col <= $highestColumnIndex; $col++) {
                    $cell = $sheet->getCellByColumnAndRow($col, $row);
                    $value = $cell->getValue();
                    
                    // Handle RichText
                    if ($value instanceof \PhpOffice\PhpSpreadsheet\RichText\RichText) {
                        $value = $value->getPlainText();
                    }
                    
                    // Check if cell has a formula
                    if ($cell->isFormula()) {
                        $value = $cell->getCalculatedValue();
                    }
                    
                    $rowData[] = $value;
                    if (!empty($value)) {
                        $hasData = true;
                    }
                }
                if ($hasData) {
                    $sheetData[] = [
                        'row' => $row,
                        'cells' => $rowData,
                    ];
                }
            }

            // Parse form structure for this sheet
            $formStructure = $this->parseSheetStructure($sheetData, $highestColumnIndex);

            // Get merged cells info
            $mergedCells = [];
            foreach ($sheet->getMergeCells() as $mergeRange) {
                $mergedCells[] = $mergeRange;
            }

            $allSheets[] = [
                'title' => $sheetTitle,
                'slug' => \Illuminate\Support\Str::slug($sheetTitle),
                'column_count' => $highestColumnIndex,
                'merged_cells' => $mergedCells,
                'sections' => $formStructure,
                'raw_data' => $sheetData,
            ];
        }

        return [
            'structure' => [
                'sheet_count' => $sheetCount,
                'form_type' => 'multi_sheet',
            ],
            'data' => [
                'sheets' => $allSheets,
            ],
        ];
    }
    
    /**
     * Parse sheet structure to detect sections and fields
     */
    private function parseSheetStructure(array $data, int $columnCount): array
    {
        $sections = [];
        $currentSection = null;
        $formTitle = '';
        
        foreach ($data as $rowInfo) {
            $row = $rowInfo['cells'];
            $rowNum = $rowInfo['row'];
            
            $firstCell = trim($row[0] ?? '');
            $nonEmptyCells = array_filter($row, fn($v) => !empty(trim($v ?? '')));
            $nonEmptyCount = count($nonEmptyCells);
            
            // Skip empty rows
            if (empty($firstCell) && $nonEmptyCount === 0) {
                continue;
            }

            // Detect form title (row 1, usually contains the main title)
            if ($rowNum === 1 && !empty($firstCell)) {
                $formTitle = $firstCell;
                continue;
            }

            // Detect section header (single cell with section-like text)
            if ($this->isSectionHeader($firstCell, $nonEmptyCount)) {
                if ($currentSection && !empty($currentSection['fields'])) {
                    $sections[] = $currentSection;
                }
                $currentSection = [
                    'title' => $firstCell,
                    'type' => 'section',
                    'fields' => [],
                    'table_headers' => null,
                ];
                continue;
            }

            // Detect table header row (multiple cells with header-like content)
            if ($nonEmptyCount >= 2 && $this->isTableHeaderRow($row)) {
                if ($currentSection) {
                    $currentSection['type'] = 'table';
                    $currentSection['table_headers'] = array_values(array_filter($row, fn($v) => !empty(trim($v ?? ''))));
                }
                continue;
            }

            // Add field or table row
            if ($currentSection) {
                if ($currentSection['type'] === 'table' && $currentSection['table_headers']) {
                    // Table row
                    $currentSection['fields'][] = [
                        'type' => 'table_row',
                        'cells' => $row,
                    ];
                } else {
                    // Regular field
                    $fieldType = $this->detectFieldType($firstCell, $row[1] ?? '');
                    $options = $this->extractOptions($row[1] ?? '');
                    
                    $field = [
                        'label' => $firstCell,
                        'type' => $fieldType,
                        'placeholder' => $row[1] ?? '',
                        'options' => $options,
                        'columns' => [],
                    ];
                    
                    // If there are more columns, add them
                    for ($i = 1; $i < $columnCount; $i++) {
                        if (!empty(trim($row[$i] ?? ''))) {
                            $field['columns'][] = [
                                'label' => $row[$i],
                                'type' => $this->detectFieldType('', $row[$i] ?? ''),
                            ];
                        }
                    }
                    
                    $currentSection['fields'][] = $field;
                }
            } else {
                // Create default section
                $currentSection = [
                    'title' => 'البيانات الأساسية',
                    'type' => 'section',
                    'fields' => [],
                    'table_headers' => null,
                ];
                
                if (!empty($firstCell) && $firstCell !== 'البيان') {
                    $currentSection['fields'][] = [
                        'label' => $firstCell,
                        'type' => $this->detectFieldType($firstCell, $row[1] ?? ''),
                        'placeholder' => $row[1] ?? '',
                        'options' => $this->extractOptions($row[1] ?? ''),
                        'columns' => [],
                    ];
                }
            }
        }

        // Add last section
        if ($currentSection && !empty($currentSection['fields'])) {
            $sections[] = $currentSection;
        }

        return $sections;
    }
    
    /**
     * Extract options from placeholder text like "[option1 / option2 / option3]"
     */
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
    
    /**
     * Check if row is a table header row
     */
    private function isTableHeaderRow(array $row): bool
    {
        $headerKeywords = ['المستوى', 'الأهمية', 'الوصف', 'التفاصيل', 'النسبة', 'الوقت', 'الموارد', 'المؤشرات', 'الأولوية', 'الحد الأدنى', 'المفضل', 'الملاحظات', 'الإجراءات'];
        
        $matchCount = 0;
        foreach ($row as $cell) {
            $cell = trim($cell ?? '');
            foreach ($headerKeywords as $keyword) {
                if (str_contains($cell, $keyword)) {
                    $matchCount++;
                    break;
                }
            }
        }
        
        return $matchCount >= 2;
    }

    /**
     * Parse form structure to detect sections and fields
     */
    private function parseFormStructure(array $data): array
    {
        $sections = [];
        $currentSection = null;
        $formType = 'table'; // default

        foreach ($data as $rowIndex => $row) {
            $firstCell = trim($row[0] ?? '');
            $secondCell = trim($row[1] ?? '');
            
            // Skip empty rows
            if (empty($firstCell) && empty($secondCell)) {
                continue;
            }

            // Detect form title (first row usually)
            if ($rowIndex === 0 && !empty($firstCell)) {
                $formType = 'form';
                continue;
            }

            // Detect section headers (cells that span multiple columns or have specific patterns)
            if ($this->isSectionHeader($row)) {
                if ($currentSection) {
                    $sections[] = $currentSection;
                }
                $currentSection = [
                    'title' => $firstCell,
                    'type' => 'section',
                    'fields' => [],
                ];
                continue;
            }

            // Detect header row for tables within sections
            if ($this->isTableHeader($row)) {
                if ($currentSection) {
                    $currentSection['table_headers'] = array_filter($row, fn($v) => !empty($v));
                    $currentSection['type'] = 'table_section';
                }
                continue;
            }

            // Regular field or table row
            if ($currentSection) {
                if ($currentSection['type'] === 'table_section') {
                    // Add as table row
                    $currentSection['fields'][] = [
                        'type' => 'table_row',
                        'values' => $row,
                    ];
                } else {
                    // Add as field
                    if (!empty($firstCell)) {
                        $fieldType = $this->detectFieldType($firstCell, $secondCell);
                        $currentSection['fields'][] = [
                            'label' => $firstCell,
                            'placeholder' => $secondCell ?: '',
                            'type' => $fieldType,
                            'value' => '',
                        ];
                    }
                }
            } else {
                // Create default section if none exists
                $currentSection = [
                    'title' => 'معلومات أساسية',
                    'type' => 'section',
                    'fields' => [],
                ];
                
                if (!empty($firstCell) && $firstCell !== 'البيان') {
                    $fieldType = $this->detectFieldType($firstCell, $secondCell);
                    $currentSection['fields'][] = [
                        'label' => $firstCell,
                        'placeholder' => $secondCell ?: '',
                        'type' => $fieldType,
                        'value' => '',
                    ];
                }
            }
        }

        // Add last section
        if ($currentSection && !empty($currentSection['fields'])) {
            $sections[] = $currentSection;
        }

        return [
            'form_type' => $formType,
            'sections' => $sections,
        ];
    }

    /**
     * Check if row is a section header
     */
    private function isSectionHeader(string $firstCell, int $nonEmptyCount): bool
    {
        // Section headers typically have only one cell (or the section title)
        if ($nonEmptyCount > 2) {
            return false;
        }

        // Common section header patterns
        $sectionPatterns = [
            'المهام', 'المسؤوليات', 'المهارات', 'المؤهلات', 'الخبرات', 'المتطلبات',
            'الصلاحيات', 'العلاقات', 'بيئة العمل', 'الكفاءات', 'الأهداف', 'معلومات',
            'البيانات', 'تحليل', 'المخاطر', 'الفرص', 'ملخص', 'التوصيات', 'معايير',
            'التقييم', 'التطوير', 'نقاط القوة', 'مجالات'
        ];

        foreach ($sectionPatterns as $pattern) {
            if (str_contains($firstCell, $pattern)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Detect field type based on label and placeholder
     */
    private function detectFieldType(string $label, string $placeholder): string
    {
        $label = mb_strtolower($label);
        $placeholder = mb_strtolower($placeholder);

        // Date fields
        if (str_contains($label, 'تاريخ') || str_contains($label, 'تحديث')) {
            return 'date';
        }

        // Number fields
        if (str_contains($label, 'راتب') || str_contains($label, 'سنوات') || str_contains($label, 'رقم')) {
            return 'number';
        }

        // Select fields (dropdown)
        if (str_contains($placeholder, '/') || str_contains($placeholder, '|')) {
            return 'select';
        }

        // Textarea for descriptions
        if (str_contains($label, 'وصف') || str_contains($label, 'مهمة') || str_contains($label, 'تفاصيل')) {
            return 'textarea';
        }

        return 'text';
    }

    /**
     * Detect column type from sample value
     */
    private function detectColumnType($value): string
    {
        if ($value === null || $value === '') {
            return 'text';
        }

        if (is_numeric($value)) {
            if (floor($value) == $value) {
                return 'integer';
            }
            return 'decimal';
        }

        if (preg_match('/^\d{4}-\d{2}-\d{2}/', $value)) {
            return 'date';
        }

        if (preg_match('/^\d+%$/', $value) || (is_numeric($value) && $value >= 0 && $value <= 1)) {
            return 'percentage';
        }

        return 'text';
    }
}
