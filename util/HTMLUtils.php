<?php
class HTMLUtils {
    public static function removeBreak($string) {
        return trim(preg_replace('/\s+/', ' ', $string));
    }

    public static function DOMinnerHTML(DOMNode $element) { 
        $innerHTML = ""; 
        $children  = $element->childNodes;

        foreach ($children as $child)
            $innerHTML .= $element->ownerDocument->saveHTML($child);

        return $innerHTML;
    } 
}