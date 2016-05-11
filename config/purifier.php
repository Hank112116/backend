<?php

return [

    'encoding' => 'UTF-8',
    'finalize' => true,
    'preload'  => false,
    'cachePath' => storage_path('purifier'),
    'settings' => [
        'default' => [
            'HTML.SafeIframe'          => true,
            'URI.SafeIframeRegexp'     => '%^(https?:)?//(www\.youtube(?:-nocookie)?\.com/embed/|player\.vimeo\.com/video/|fusion360\.autodesk\.com/models/|www\.kickstarter\.com/pages/|www\.indiegogo\.com/project/)%',
            'AutoFormat.AutoParagraph' => true,
            'AutoFormat.RemoveEmpty'   => false,
        ]
    ],

];
