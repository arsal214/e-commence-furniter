<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmailTemplate;
use App\Support\EmailHtml;
use Illuminate\Http\Request;

/**
 * Saved copy for promotional emails, loaded into the compose screen with one
 * click. Staff write the seasonal-sale wording once instead of retyping it for
 * every customer they mail.
 */
class EmailTemplateController extends Controller
{
    public function index()
    {
        return view('admin.email-templates.index', [
            'templates' => EmailTemplate::ordered()->get(),
        ]);
    }

    public function create()
    {
        return view('admin.email-templates.create');
    }

    public function store(Request $request)
    {
        EmailTemplate::create($this->validated($request));

        return redirect()->route('admin.email-templates.index')->with('success', 'Template created.');
    }

    public function edit(EmailTemplate $emailTemplate)
    {
        return view('admin.email-templates.edit', ['template' => $emailTemplate]);
    }

    public function update(Request $request, EmailTemplate $emailTemplate)
    {
        $emailTemplate->update($this->validated($request));

        return redirect()->route('admin.email-templates.index')->with('success', 'Template updated.');
    }

    public function destroy(EmailTemplate $emailTemplate)
    {
        $emailTemplate->delete();

        return redirect()->route('admin.email-templates.index')->with('success', 'Template deleted.');
    }

    /**
     * @return array<string, mixed>
     */
    protected function validated(Request $request): array
    {
        $data = $request->validate([
            'name'       => 'required|string|max:80',
            'subject'    => 'required|string|max:180',
            'eyebrow'    => 'nullable|string|max:60',
            'heading'    => 'nullable|string|max:120',
            'body_html'  => 'required|string|max:60000',
            'cta_label'  => 'nullable|string|max:40',
            'cta_url'    => 'nullable|url|max:500',
            'promo_code' => 'nullable|string|max:40',
            'promo_note' => 'nullable|string|max:160',
            'sort_order' => 'nullable|integer|min:0|max:9999',
        ]);

        // Sanitised on the way in as well as on the way out: a template body is
        // read back into the editor, and storing clean markup keeps it clean.
        $data['body_html'] = EmailHtml::sanitize($data['body_html']);
        $data['is_active'] = $request->boolean('is_active');
        $data['sort_order'] = $data['sort_order'] ?? 0;

        return $data;
    }
}
