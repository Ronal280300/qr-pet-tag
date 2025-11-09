<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmailTemplate;
use Illuminate\Http\Request;

class EmailTemplateController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(\App\Http\Middleware\AdminOnly::class);
    }

    /**
     * Listar todas las plantillas
     */
    public function index()
    {
        $templates = EmailTemplate::orderBy('created_at', 'desc')->paginate(15);

        return view('portal.admin.email-templates.index', compact('templates'));
    }

    /**
     * Mostrar formulario de creación
     */
    public function create()
    {
        $categories = EmailTemplate::getCategories();

        return view('portal.admin.email-templates.create', compact('categories'));
    }

    /**
     * Guardar nueva plantilla
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'html_content' => 'required|string',
            'category' => 'required|string',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        EmailTemplate::create($validated);

        return redirect()
            ->route('portal.admin.email-templates.index')
            ->with('success', 'Plantilla creada exitosamente');
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit(EmailTemplate $emailTemplate)
    {
        $categories = EmailTemplate::getCategories();

        return view('portal.admin.email-templates.edit', compact('emailTemplate', 'categories'));
    }

    /**
     * Actualizar plantilla
     */
    public function update(Request $request, EmailTemplate $emailTemplate)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'html_content' => 'required|string',
            'category' => 'required|string',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $emailTemplate->update($validated);

        return redirect()
            ->route('portal.admin.email-templates.index')
            ->with('success', 'Plantilla actualizada exitosamente');
    }

    /**
     * Eliminar plantilla
     */
    public function destroy(EmailTemplate $emailTemplate)
    {
        // Verificar si tiene campañas asociadas
        if ($emailTemplate->campaigns()->count() > 0) {
            return redirect()
                ->back()
                ->with('error', 'No se puede eliminar una plantilla con campañas asociadas');
        }

        $emailTemplate->delete();

        return redirect()
            ->route('portal.admin.email-templates.index')
            ->with('success', 'Plantilla eliminada exitosamente');
    }

    /**
     * Previsualizar plantilla
     */
    public function preview(EmailTemplate $emailTemplate)
    {
        $user = auth()->user(); // Usuario de ejemplo para preview

        $html = $emailTemplate->renderForUser($user);

        return response($html)->header('Content-Type', 'text/html');
    }

    /**
     * Duplicar plantilla
     */
    public function duplicate(EmailTemplate $emailTemplate)
    {
        $newTemplate = $emailTemplate->replicate();
        $newTemplate->name = $emailTemplate->name . ' (Copia)';
        $newTemplate->save();

        return redirect()
            ->route('portal.admin.email-templates.index')
            ->with('success', 'Plantilla duplicada exitosamente');
    }
}
