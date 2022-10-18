<?php
namespace App\Helpers;

class Text {

    public static function excerpt(string $content, int $limit = 60)
    {
        if (strlen($content) <= $limit) {
            return $content;
        }
        $lastSpace = strpos($content, ' ', $limit);
        return substr($content, 0, $lastSpace) . '...';
    }
}