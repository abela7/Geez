<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class ShiftsTemplatesController extends Controller
{
    public function index(): View
    {
        $totalTemplates = 0;
        $activeTemplates = 0;
        $draftTemplates = 0;
        $totalUsage = 0;
        $templateTypes = [];
        $popularTemplates = [];
        $recentTemplates = [];
        $templates = [];

        return view('admin.shifts.templates.index', compact(
            'totalTemplates',
            'activeTemplates',
            'draftTemplates',
            'totalUsage',
            'templateTypes',
            'popularTemplates',
            'recentTemplates',
            'templates'
        ));
    }
}
