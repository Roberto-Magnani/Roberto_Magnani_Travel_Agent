<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;
use App\Services\OpenAiService;
use App\Services\QdrantService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use App\Services\DocumentSplitterService;

class DocumentController extends Controller
{
    public function index()
    {
        $documents = Document::all();

        return view('documents.index' , compact('documents'));
    }

    public function store(Request $request)
    {
        // Validate the request
        $request->validate([
            'name' => 'required',
            'file' => 'required|file'
        ]);
        
        // Store the file
        $file = $request->file('file');
        $path = $file->store('documents' , 'public');



        // Create the document
        $document = Document::create([
            'name' => $request->name,
            'path' => $path,
            'mime' => $file->getClientMimeType(),
            'size' => $file->getSize()
        ]);


        // Send the document to Fast API Server
        $response = Http::attach(
            'file', 
            file_get_contents(storage_path('app/public/' . $path)), 
            $request->name . "." . $file->extension(),
        )->post('http://127.0.0.1:8080/vectord-db/insert' , [
            "document_id" => $document->id
        ]);

        return redirect()->route('documents.index');
    }

    public function download(Document $document)
    {   
        $ext = pathinfo($document->path, PATHINFO_EXTENSION);

        return response()->download(storage_path('app/' . $document->path) , $document->name . '.' . $ext);
    }

    public function destroy(Document $document)
    {
        Storage::delete($document->path);
        Http::delete('http://127.0.0.1:8080/vectord-db/delete/' . $document->id);
        $document->delete();

        return redirect()->route('documents.index');
    }
}
