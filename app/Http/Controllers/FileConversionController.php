<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FileConversionController extends Controller
{
    public function index()
    {
        return view('upload');
    }

    public function upload(Request $request)
    {
        if ($request->hasFile('file')) {
            $request->validate([
                'file' => 'required|mimetypes:text/xml,application/xml,application/x-xml,application/octet-stream,text/plain',
            ]);

            $file = $request->file('file');
            $path = $file->storeAs('uploads', $file->getClientOriginalName());

            // Convert XML to CSV
            $csvPath = $this->convertXmlToCsv(storage_path('app/' . $path));

            // Return the CSV file for download
            return response()->download($csvPath);
        }

        return redirect()->back()->withInput()->withErrors(['file' => 'Please upload a valid XML file.']);
    }

    private function convertXmlToCsv($xmlFilePath)
    {
        $csvFilePath = storage_path('app/csv/data.csv');

        // Ensure the directory exists
        if (!file_exists(dirname($csvFilePath))) {
            mkdir(dirname($csvFilePath), 0755, true);
        }

        $xml = simplexml_load_file($xmlFilePath);
        $csvFile = fopen($csvFilePath, 'w');

        // Write headers
        fputcsv($csvFile, [
            'course_display_name',
            'chapter_url_name',
            'wiki_slug',
            'course_start_date',
            'course_language',
            'course_mobile_available',
            'course_cert_html_view_enabled',
            'advanced_modules',
        ]);

        // Write data
        foreach ($xml->chapter as $chapter) {
            $chapterData = [
                (string)$xml['display_name'], // course_display_name
                (string)$chapter['url_name'], // chapter_url_name
                (string)$xml->wiki['slug'],   // wiki_slug
                (string)$xml['start'],       // course_start_date
                (string)$xml['language'],    // course_language
                (string)$xml['mobile_available'],  // course_mobile_available
                (string)$xml['cert_html_view_enabled'],  // course_cert_html_view_enabled
                implode(', ', json_decode(html_entity_decode($xml['advanced_modules']), true)), // advanced_modules
            ];
            fputcsv($csvFile, $chapterData);
        }

        fclose($csvFile);
        return $csvFilePath;
    }
}
