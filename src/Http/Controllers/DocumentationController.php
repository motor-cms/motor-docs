<?php

namespace Motor\Docs\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Request;

/**
 * Class Controller
 * @package Motor\Docs\Http\Controllers
 */
class DocumentationController extends Controller
{

    protected $config;

    public function index($package, $page)
    {
        $parser     = new \ParsedownExtra();
        $navigation = '';
        $this->config     = config('motor-docs.packages');
        foreach ($this->config as $documentationPackage => $packageConfig) {
            $navigation .= $parser->text(documentation($documentationPackage.'::'.$packageConfig['navigation']));
        }

        if (Request::get('search') != '') {
            $query = Request::get('search');
            $searchResult = $this->search($query);
            return view('motor-docs::documentation.search', compact('navigation', 'searchResult', 'query'));
        }

        $content = $parser->text(documentation($package.'::'.$page));

        return view('motor-docs::documentation.index', compact('navigation', 'content'));
    }


    protected function search($query)
    {
        $fileList = getAllDocumentationFiles();
        $results = [];

        foreach ($fileList as $file) {
            // Get package from file
            $package = '';
            foreach ($this->config as $documentationPackage => $packageConfig) {
                if (strpos($file, $documentationPackage) !== false) {
                    $package = $documentationPackage;
                }
            }

            // Get relative filename and strip extension
            $fileName = substr($file, strrpos($file, 'documentation/')+14, -3);

            // Load file and strip special chars
            $content = file_get_contents($file);
            $content = preg_replace("/[\*|\`|\[|\]|\#]/im", "", $content);

            // Split search query
            $searchTerms = preg_split("/ /", $query, NULL, PREG_SPLIT_NO_EMPTY);

            $matches = 0;
            // Search file for matches
            foreach ($searchTerms as $term) {
                $match = preg_match("/.*$term.*/im", $content, $regexArray);
                if ($match) {
                    $matches ++;
                    // Create temporary array with matches
                    for ($i = 0; $i < count($regexArray); $i++) {
                        $tempResultArray[$regexArray[$i]] = 1; //value doens't matter... basically just creating a set
                    }
                }
            }

            if ($matches == count($searchTerms)) {
                $contextArray = array();
                foreach ($tempResultArray as $line => $value) {
                    $contextArray[] = $line;
                }
                foreach ($searchTerms as $term) { // bold the search terms in the results
                    for ($i = 0; $i < count($contextArray); $i++) {
                        $contextArray[$i] = preg_replace("/\**($term)\**/im", "<span class=\"found\">$1</span>", $contextArray[$i]);
                    }
                }
                foreach ($contextArray as $foundResult) {
                    $results[] = ['content' => "..." . $foundResult . "...", 'package' => $package, 'file' => $fileName]; // wrap context in elipses
                }
            }
        }
        return $results;
    }
}
