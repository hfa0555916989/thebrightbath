<?php

namespace App\Http\Controllers;

use App\Models\AnalysisModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AnalysisModelController extends Controller
{
    /**
     * Display listing of analysis models (public)
     */
    public function index()
    {
        $models = AnalysisModel::active()
            ->ordered()
            ->get();

        $featuredModels = $models->where('is_featured', true);

        return view('analysis-models.index', compact('models', 'featuredModels'));
    }

    /**
     * Display a single analysis model (public)
     */
    public function show(AnalysisModel $model)
    {
        if (!$model->is_active) {
            abort(404);
        }

        $model->incrementViews();

        return view('analysis-models.show', compact('model'));
    }

    /**
     * Export/Download the original Excel file
     */
    public function download(AnalysisModel $model)
    {
        if (!$model->is_active || !$model->file_path) {
            abort(404);
        }

        if (!Storage::disk('private')->exists($model->file_path)) {
            abort(404, 'الملف غير موجود');
        }

        $model->incrementDownloads();

        return Storage::disk('private')->download($model->file_path, $model->original_file_name);
    }
}
