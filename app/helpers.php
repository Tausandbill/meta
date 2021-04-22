<?php
if (!function_exists('authorString')) {
    function authorString($authors)
    {
        $authorString = "";
        foreach ($authors as $key => $author) {
            $authorString = $authorString . $author['name'] . ", ";
        }
        $authorString = substr($authorString, 0, -2);

        return $authorString;
    }
}
