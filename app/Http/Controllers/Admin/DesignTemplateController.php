<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DesignTemplate;
use Illuminate\Http\Request;

class DesignTemplateController extends Controller
{
   public function index()
    {
        $templates = DesignTemplate::orderBy('id', 'desc')->get();

        return view('admin.design_templates.index', compact('templates'));
    }

    public function create()
    {
        return view('admin.design_templates.create');
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'name'          => 'required|string|max:255',
            'type'          => 'required|in:frame,card',
            'category'      => 'nullable|string|max:255',
            'canvas_width'  => 'required|integer|min:100',
            'canvas_height' => 'required|integer|min:100',
        ]);

        DesignTemplate::create([
            'name'          => $request->name,
            'type'          => $request->type,
            'category'      => $request->category,
            'canvas_width'  => $request->canvas_width,
            'canvas_height' => $request->canvas_height,
            'is_active'     => true,
        ]);

        return redirect()
            ->route('admin.design-templates.index')
            ->with('success', 'Template created successfully');
    }

    public function editor(DesignTemplate $template)
    {
        return view('admin.design_templates.editor', compact('template'));
    }

    public function saveLayout(Request $request, DesignTemplate $template)
    {
        $request->validate([
            'template_json' => 'required|string',
        ]);

        $template->update([
            'template_json' => $request->template_json,
        ]);

        return response()->json([
            'success' => true
        ]);
    }

    
    public function uploadSvg(Request $request)
    {
        $request->validate([
            'svg' => 'required|file|mimes:svg|max:2048',
        ]);

        $path = $request->file('svg')->store('template-svgs', 'public');

        return response()->json([
            'success' => true,
            'url' => asset('storage/' . $path)
        ]);
    }

}
