<?php

namespace App\Http\Controllers;

use App\Models\DesignTemplate;
use Illuminate\Http\Request;

class TemplateController extends Controller
{
   public function index()
    {
        $templates = DesignTemplate::where('is_active', 1)->get();

        return view('templates.index', compact('templates'));
    }


    public function use(DesignTemplate $template)
    {
        return view('templates.use', [
            'template' => $template
        ]);
    }

}
