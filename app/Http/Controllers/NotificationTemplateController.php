<?php

namespace App\Http\Controllers;

use App\Models\NotificationTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Laracasts\Flash\Flash;

class NotificationTemplateController extends Controller
{
    public function index()
    {
        $templates = NotificationTemplate::all();
        return view('sadmin.notification_templates.index', compact('templates'));
    }

    public function edit($id)
    {
        $template = NotificationTemplate::findOrFail($id);
        return view('sadmin.notification_templates.edit', compact('template'));
    }

    public function update(Request $request, $id)
    {
        $template = NotificationTemplate::findOrFail($id);
        $request->validate([
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
        ]);
        $template->update([
            'subject' => $request->subject,
            'body' => $request->body,
        ]);
        Flash::success('Notification template updated successfully.');
        return Redirect::route('notification-templates.index');
    }

    public function create()
    {
        return view('sadmin.notification_templates.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|string|unique:notification_templates,type',
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
            'variables' => 'nullable|string',
        ]);
        $variables = $request->variables ? array_map('trim', explode(',', $request->variables)) : [];
        $template = \App\Models\NotificationTemplate::create([
            'type' => $request->type,
            'subject' => $request->subject,
            'body' => $request->body,
            'variables' => $variables,
        ]);
        \Laracasts\Flash\Flash::success('Notification template created successfully.');
        return \Illuminate\Support\Facades\Redirect::route('notification-templates.index');
    }

    public function sendTest(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);
        $templates = \App\Models\NotificationTemplate::all();
        foreach ($templates as $template) {
            $subject = $template->subject;
            $body = $template->body;
            $variables = $template->variables ?? [];
            if (is_string($variables)) {
                $variables = json_decode($variables, true) ?: [];
            }
            $replace = [];
            foreach ($variables as $var) {
                $replace['{' . $var . '}'] = strtoupper($var) . '_TEST';
            }
            $subject = strtr($subject, $replace);
            $body = strtr($body, $replace);
            \Mail::raw(strip_tags($body), function ($message) use ($request, $subject) {
                $message->to($request->email)->subject($subject);
            });
        }
        \Laracasts\Flash\Flash::success('Test emails sent to ' . $request->email);
        return \Illuminate\Support\Facades\Redirect::route('notification-templates.index');
    }
} 