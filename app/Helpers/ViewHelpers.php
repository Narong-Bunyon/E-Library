<?php

namespace App\Helpers;

class ViewHelpers
{
    /**
     * Get color for category
     */
    public static function getCategoryColor($categoryName)
    {
        $colors = [
            'Programming' => '#007bff',
            'Design' => '#28a745',
            'Database' => '#ffc107',
            'Web Development' => '#17a2b8',
            'Mobile' => '#6f42c1',
            'DevOps' => '#fd7e14',
            'Security' => '#dc3545',
            'AI/ML' => '#20c997',
        ];
        
        return $colors[$categoryName] ?? '#6c757d';
    }
}
