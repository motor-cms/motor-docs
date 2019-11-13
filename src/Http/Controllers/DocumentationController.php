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


    /**
     * Main controller method. Finds documents by parsing package and page info. Also does initiate the search!
     *
     * @param $package
     * @param $page
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Exception
     */
    public function index($package, $page)
    {
        $parser       = new \ParsedownExtra();
        $navigation   = '';
        $this->config = config('motor-docs.packages');

        // Sort packages by position
        uasort($this->config, function($a, $b) {
            return $a['position'] <=> $b['position'];
        });

        foreach ($this->config as $documentationPackage => $packageConfig) {
            $document   = ($documentationPackage === 'local' ? $packageConfig['navigation'] : $documentationPackage.'::'.$packageConfig['navigation']);
            $navigation .= $parser->text(documentation($document));
        }

        if (Request::get('search') != '') {
            $query        = Request::get('search');
            $searchResult = $this->search($query);

            return view('motor-docs::documentation.search', compact('navigation', 'searchResult', 'query'));
        }

        // If the first parameter is empty, we're guessing that this is a local document
        $document = ($page === '' ? $package : $package.'::'.$page);
        $content  = $parser->text(documentation($document));

        return view('motor-docs::documentation.index', compact('navigation', 'content'));
    }


    /**
     * Simple and very hackish full text search over all markdown files
     *
     * @param $query
     * @return array
     */
    protected function search($query)
    {
        $fileList = getAllDocumentationFiles();

        $results  = [];

        foreach ($fileList as $file) {
            // Get package from file
            $package = 'local';
            foreach ($this->config as $documentationPackage => $packageConfig) {
                if (strpos($file, $documentationPackage) !== false) {
                    $package = $documentationPackage;
                }
            }

            // Get relative filename and strip extension
            $fileName = substr($file, strrpos($file, 'documentation/') + 14, -3);

            // Load file and strip special chars
            $content = file_get_contents($file);
            $content = preg_replace("/[\*|\`|\[|\]|\#]/im", "", $content);

            // Split search query
            $searchTerms = preg_split("/ /", $query, null, PREG_SPLIT_NO_EMPTY);

            $matches = 0;
            // Search file for matches
            foreach ($searchTerms as $term) {
                $match = preg_match("/.*$term.*/im", $content, $regexArray);
                if ($match) {
                    $matches++;
                    // Create temporary array with matches
                    for ($i = 0; $i < count($regexArray); $i++) {
                        $tempResultArray[$regexArray[$i]] = 1; //value doens't matter... basically just creating a set
                    }
                }
            }

            if ($matches == count($searchTerms)) {
                $contextArray = [];
                foreach ($tempResultArray as $line => $value) {
                    $contextArray[] = $line;
                }
                foreach ($searchTerms as $term) { // bold the search terms in the results
                    for ($i = 0; $i < count($contextArray); $i++) {
                        $contextArray[$i] = preg_replace("/\**($term)\**/im", "<span class=\"found\">$1</span>",
                            $contextArray[$i]);
                    }
                }
                foreach ($contextArray as $foundResult) {
                    $results[] = [
                        'content' => "...".$foundResult."...",
                        'package' => $package,
                        'file'    => $fileName
                    ]; // wrap context in elipses
                }
            }
        }

        return $results;
    }
}
