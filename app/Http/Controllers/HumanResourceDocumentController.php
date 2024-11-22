<?php

namespace App\Http\Controllers;

use App\Models\HumanResourceDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HumanResourceDocumentController extends Controller
{

    public function index(Request $request)
    {
        $search = $request->input('search');
    
        $documents = HumanResourceDocument::when($search, function ($query) use ($search) {
            return $query->where('title', 'LIKE', "%{$search}%");
        })
        ->paginate(10); 
    
        return view('admin.pages.hrdocuments.index', compact('documents', 'search'));
    }

    public function archivedDocuments(Request $request)
    {
        $search = $request->input('search');
    
        $documents = HumanResourceDocument::onlyTrashed()->when($search, function ($query) use ($search) {
            return $query->where('title', 'LIKE', "%{$search}%");
        })
        ->paginate(10); 
    
        return view('admin.pages.hrdocuments.archives', compact('documents', 'search'));
    }

    public function create()
    {
        return view('admin.pages.hrdocuments.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'file' => 'required',
        ]);

        $file = $request->file('file');
        $fileName = date('Ymdhis') . '.' . $file->getClientOriginalExtension();
        
        $path = $file->storeAs('public/hr/documents', $fileName);

        HumanResourceDocument::create([
            'title' => $request->title,
            'file_path' => $path,
            'file_type' => $file->extension(),
        ]);

        notify()->success('Document uploaded successfully');
        return redirect()->route('admin.pages.hrdocuments.index');
    }
    
    public function edit(HumanResourceDocument $hrdocument)
    {
        return view('admin.pages.hrdocuments.edit', compact('hrdocument'));
    }
    
    public function update(Request $request, HumanResourceDocument $hrdocument)
    {

        $updateData = ['title' => $request->title];
    
        // Check if there's a new file uploaded
        if ($request->hasFile('file')) {
            Storage::delete($hrdocument->file_path);
            $file = $request->file('file');
            $fileName = date('Ymdhis') . '.' . $file->getClientOriginalExtension();
            
            // Store the new file and set file path and type
            $path = $file->storeAs('public/hr/documents', $fileName);
            $updateData['file_path'] = $path;
            $updateData['file_type'] = $file->extension();
        }

        $hrdocument->update($updateData);
    
        // Notify and redirect
        notify()->success('Document updated successfully');
        return redirect()->route('admin.pages.hrdocuments.index');
    }

    public function permanentDelete($id)
    {
        $hrdocument = HumanResourceDocument::withTrashed()->findOrFail($id);    
        Storage::delete($hrdocument->file_path);
        $hrdocument->forceDelete();

        notify()->success('Document ahs been deleted permanently');
        return redirect()->route('archived-documents');
    }

    public function destroy(HumanResourceDocument $hrdocument)
    {
        $hrdocument->delete();

        notify()->success('Document has been moved to archives successfully');
        return redirect()->route('admin.pages.hrdocuments.index');
    }

    public function restore($id)
    {
        $document = HumanResourceDocument::withTrashed()->findOrFail($id);
        $document->restore();

        notify()->success('Document restored successfully');
        return redirect()->route('archived-documents');
    }
}