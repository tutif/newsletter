<?php
declare(strict_types=1);

namespace App\Service;

class CategoryProvider
{
    /**
     * @return string[]
     */
    public function getCategories(): array
    {
        return [
            'New Product Announcements',
            'Coupons and Promotions',
            'Gift Guides',
            'Refer-a-Friend Program',
            'Case Studies and Testimonials',
            'Show Off User-Generated Content',
            'Moonshine Recipes',
            'Software Release Announcements',
        ];
    }
}
