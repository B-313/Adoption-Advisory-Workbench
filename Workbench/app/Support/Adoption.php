<?php

namespace App\Support;

class Adoption
{
    public const SECTORS = [
        'Information & Communication', 'Business Services', 'Finance & Real Estate',
        'Manufacturing', 'Construction', 'Retail & Distribution',
        'Transport & Logistics', 'Hotel & Catering', 'Other',
    ];

    public const BENCH = [
        'Information & Communication' => 43, 'Business Services' => 23, 'Finance & Real Estate' => 21,
        'Manufacturing' => 18, 'Construction' => 14, 'Retail & Distribution' => 13,
        'Transport & Logistics' => 12, 'Hotel & Catering' => 12, 'Other' => 16,
    ];

    public const TECHS = ['Machine Learning', 'NLP', 'Computer Vision', 'Agentic AI'];

    public const SIZES = ['Micro', 'Small', 'Mid', 'Large'];

    public const STATES = ['Current user', 'Planner', 'Non-adopter'];

    public const BARRIERS = [
        ['No identified need', 71], ['Skills gap', 60], ['Lack of tools', 48],
        ['Cost', 38], ['Unclear regulation', 34], ['Ethical concerns', 30],
    ];

    public const PHASES = [
        ['name' => 'Diagnosis',   'max' => 24,  'color' => '#dc2626',
         'desc' => 'Frame the core problem and where AI could matter.',
         'support' => 'Problem-definition workshops · Low-cost diagnostic methods'],
        ['name' => 'Discovery',   'max' => 44,  'color' => '#d4a017',
         'desc' => 'Research user needs and the affected operations.',
         'support' => 'User research guides · Sector benchmark evidence'],
        ['name' => 'Development', 'max' => 64,  'color' => '#34b97f',
         'desc' => 'Turn insight into testable AI use-case ideas.',
         'support' => '24-hour rapid workshops · Collaborative prototyping'],
        ['name' => 'Delivery',    'max' => 101, 'color' => '#059669',
         'desc' => 'Move from prototype to scaled implementation.',
         'support' => 'Delivery playbooks · Scaling & governance protocols'],
    ];
}
