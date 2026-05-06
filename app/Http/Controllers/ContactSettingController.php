<?php

namespace App\Http\Controllers;

use App\Models\ContactSetting;
use App\Models\User;
use Illuminate\Http\Request;

class ContactSettingController extends Controller
{
    public function index()
    {
        $contacts = ContactSetting::with('admin')->latest()->get();
        return view('admin.contact-setting.index', compact('contacts'));
    }

    public function create()
    {
        $admins = User::whereHas('role', fn($q) => $q->where('role_name', 'admin'))->get();
        return view('admin.contact-setting.create', compact('admins'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'admin_user_id' => 'required|exists:users,id',
            'contact_name' => 'required|string|max:255',
            'whatsapp_number' => 'required|string|max:30',
            'whatsapp_url' => 'nullable|string|max:255',
            'message_template' => 'nullable|string',
            'is_active' => 'required|boolean',
        ]);

        ContactSetting::create($validated);

        return redirect()->route('contact-settings.index')->with('success', 'Kontak berhasil ditambahkan.');
    }

    public function edit(ContactSetting $contactSetting)
    {
        $admins = User::whereHas('role', fn($q) => $q->where('role_name', 'admin'))->get();
        return view('admin.contact-setting.edit', compact('contactSetting', 'admins'));
    }

    public function update(Request $request, ContactSetting $contactSetting)
    {
        $validated = $request->validate([
            'admin_user_id' => 'required|exists:users,id',
            'contact_name' => 'required|string|max:255',
            'whatsapp_number' => 'required|string|max:30',
            'whatsapp_url' => 'nullable|string|max:255',
            'message_template' => 'nullable|string',
            'is_active' => 'required|boolean',
        ]);

        $contactSetting->update($validated);

        return redirect()->route('contact-settings.index')->with('success', 'Kontak berhasil diperbarui.');
    }

    public function destroy(ContactSetting $contactSetting)
    {
        $contactSetting->delete();

        return redirect()->route('contact-settings.index')->with('success', 'Kontak berhasil dihapus.');
    }
}
