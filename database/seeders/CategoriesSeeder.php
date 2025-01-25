<?php

namespace Database\Seeders;

use Spatie\Tags\Tag;
use Illuminate\Database\Seeder;

class CategoriesSeeder extends Seeder
{
    public function run()
    {
        $categories = [
            [
                'name' => 'E-commerce',
                'slug' => 'e-commerce',
                'description' => 'Open source e-commerce solutions and online store platforms',
            ],
            [
                'name' => 'Social Network',
                'slug' => 'social-network',
                'description' => 'Social media platforms, community forums, and networking solutions',
            ],
            [
                'name' => 'Blog',
                'slug' => 'blog',
                'description' => 'Blogging platforms and content publishing systems',
            ],
            [
                'name' => 'CMS',
                'slug' => 'cms',
                'description' => 'Content Management Systems and website builders',
            ],
            [
                'name' => 'Learning Management',
                'slug' => 'learning-management',
                'description' => 'Educational platforms and course management systems',
            ],
            [
                'name' => 'Project Management',
                'slug' => 'project-management',
                'description' => 'Task tracking, team collaboration, and project organization tools',
            ],
            [
                'name' => 'Analytics',
                'slug' => 'analytics',
                'description' => 'Data analysis, reporting, and visualization platforms',
            ],
            [
                'name' => 'API Platform',
                'slug' => 'api-platform',
                'description' => 'API development and management solutions',
            ],
            [
                'name' => 'Booking System',
                'slug' => 'booking-system',
                'description' => 'Appointment scheduling and reservation systems',
            ],
            [
                'name' => 'HR Management',
                'slug' => 'hr-management',
                'description' => 'Human resources and employee management systems',
            ]
        ];
    }
}
