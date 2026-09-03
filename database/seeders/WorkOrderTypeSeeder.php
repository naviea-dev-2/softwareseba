<?php

// database/seeders/WorkOrderTypeSeeder.php

namespace Database\Seeders;

use App\Models\WorkOrderType;
use Illuminate\Database\Seeder;

class WorkOrderTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [

            /*
            |--------------------------------------------------------------------------
            | 1. Production / Manufacturing
            |--------------------------------------------------------------------------
            */
            [
                'name'        => 'Production / Manufacturing',
                'slug'        => 'production',
                'description' => 'Convert raw materials into finished goods through a planned production process.',
                'config'      => [
                    'fields' => [

                        'title' => [
                            'show'    => true,
                            'required' => true,
                        ],

                        'description' => [
                            'show'     => true,
                            'required' => false,
                        ],

                        'customer_id' => [
                            'show'     => true,
                            'required' => false,
                        ],

                        'vendor_id' => [
                            'show'     => false,
                            'required' => false,
                        ],

                        'assigned_to' => [
                            'show'     => true,
                            'required' => true,
                            'label'    => 'Production Supervisor',
                        ],

                        'warehouse_id' => [
                            'show'     => true,
                            'required' => true,
                            'label'    => 'Production Floor / Warehouse',
                        ],

                        'priority' => [
                            'show'     => true,
                            'required' => true,
                        ],

                        'due_at' => [
                            'show'     => true,
                            'required' => true,
                            'label'    => 'Production Due Date',
                        ],

                        'estimated_cost' => [
                            'show'     => true,
                            'required' => false,
                        ],

                        'estimated_hours' => [
                            'show'     => true,
                            'required' => false,
                        ],

                        'reference_no' => [
                            'show'     => true,
                            'required' => false,
                            'label'    => 'Sales / Production Ref',
                        ],
                    ],

                    'sections' => [

                        [
                            'title'  => 'Production Details',
                            'fields' => [

                                [
                                    'name'     => 'output_product_id',
                                    'label'    => 'Finished Product',
                                    'type'     => 'select-product',
                                    'required' => true,
                                ],

                                [
                                    'name'     => 'quantity_to_produce',
                                    'label'    => 'Target Quantity',
                                    'type'     => 'number',
                                    'required' => true,
                                ],

                                [
                                    'name'     => 'unit',
                                    'label'    => 'Unit',
                                    'type'     => 'text',
                                    'required' => false,
                                ],

                                [
                                    'name'     => 'production_batch',
                                    'label'    => 'Batch / Lot No.',
                                    'type'     => 'text',
                                    'required' => false,
                                ],

                                [
                                    'name'     => 'machine_line',
                                    'label'    => 'Machine / Production Line',
                                    'type'     => 'text',
                                    'required' => false,
                                ],

                                [
                                    'name'     => 'shift',
                                    'label'    => 'Production Shift',
                                    'type'     => 'select',
                                    'options'  => [
                                        'Morning',
                                        'Evening',
                                        'Night',
                                    ],
                                    'required' => false,
                                ],
                            ],
                        ],

                        [
                            'title'  => 'Quality Control',
                            'fields' => [

                                [
                                    'name'     => 'quality_check_required',
                                    'label'    => 'Quality Check Required',
                                    'type'     => 'checkbox',
                                    'required' => false,
                                ],

                                [
                                    'name'     => 'quality_standard',
                                    'label'    => 'Quality Standard',
                                    'type'     => 'text',
                                    'required' => false,
                                ],
                            ],
                        ],
                    ],

                    'line_items' => [
                        'enabled' => true,
                        'label'   => 'Bill of Materials / Raw Materials',
                        'types'   => [
                            'product',
                            'material',
                        ],
                    ],
                ],
            ],

            /*
            |--------------------------------------------------------------------------
            | 2. Software Development
            |--------------------------------------------------------------------------
            */
            [
                'name'        => 'Software Development',
                'slug'        => 'software-development',
                'description' => 'Software development tasks including new features, modules, APIs and application development.',
                'config'      => [
                    'fields' => [

                        'title' => [
                            'show'     => true,
                            'required' => true,
                        ],

                        'description' => [
                            'show'     => true,
                            'required' => true,
                        ],

                        'customer_id' => [
                            'show'     => true,
                            'required' => false,
                        ],

                        'vendor_id' => [
                            'show'     => false,
                            'required' => false,
                        ],

                        'assigned_to' => [
                            'show'     => true,
                            'required' => true,
                            'label'    => 'Developer / Assignee',
                        ],

                        'warehouse_id' => [
                            'show'     => false,
                            'required' => false,
                        ],

                        'priority' => [
                            'show'     => true,
                            'required' => true,
                        ],

                        'due_at' => [
                            'show'     => true,
                            'required' => true,
                            'label'    => 'Development Deadline',
                        ],

                        'estimated_cost' => [
                            'show'     => true,
                            'required' => false,
                        ],

                        'estimated_hours' => [
                            'show'     => true,
                            'required' => true,
                        ],

                        'reference_no' => [
                            'show'     => true,
                            'required' => false,
                            'label'    => 'Ticket / Issue / Sprint Ref',
                        ],
                    ],

                    'sections' => [

                        [
                            'title'  => 'Project Information',
                            'fields' => [

                                [
                                    'name'     => 'project_name',
                                    'label'    => 'Project / Product',
                                    'type'     => 'text',
                                    'required' => true,
                                ],

                                [
                                    'name'     => 'module_name',
                                    'label'    => 'Module / Feature',
                                    'type'     => 'text',
                                    'required' => true,
                                ],

                                [
                                    'name'     => 'task_type',
                                    'label'    => 'Task Type',
                                    'type'     => 'select',
                                    'options'  => [
                                        'New Feature',
                                        'Enhancement',
                                        'API Development',
                                        'Database Development',
                                        'Integration',
                                        'Refactoring',
                                        'Documentation',
                                        'Other',
                                    ],
                                    'required' => true,
                                ],

                                [
                                    'name'     => 'development_type',
                                    'label'    => 'Development Type',
                                    'type'     => 'select',
                                    'options'  => [
                                        'Full Stack',
                                        'Backend',
                                        'Frontend',
                                        'Mobile',
                                        'Database',
                                        'DevOps',
                                        'QA',
                                    ],
                                    'required' => false,
                                ],
                            ],
                        ],

                        [
                            'title'  => 'Technical Details',
                            'fields' => [

                                [
                                    'name'     => 'technology_stack',
                                    'label'    => 'Technology / Stack',
                                    'type'     => 'text',
                                    'required' => false,
                                ],

                                [
                                    'name'     => 'programming_language',
                                    'label'    => 'Programming Language',
                                    'type'     => 'text',
                                    'required' => false,
                                ],

                                [
                                    'name'     => 'framework',
                                    'label'    => 'Framework',
                                    'type'     => 'text',
                                    'required' => false,
                                ],

                                [
                                    'name'     => 'database',
                                    'label'    => 'Database',
                                    'type'     => 'text',
                                    'required' => false,
                                ],

                                [
                                    'name'     => 'repository',
                                    'label'    => 'Repository',
                                    'type'     => 'text',
                                    'required' => false,
                                ],

                                [
                                    'name'     => 'branch',
                                    'label'    => 'Git Branch',
                                    'type'     => 'text',
                                    'required' => false,
                                ],
                            ],
                        ],

                        [
                            'title'  => 'Development & Release',
                            'fields' => [

                                [
                                    'name'     => 'environment',
                                    'label'    => 'Environment',
                                    'type'     => 'select',
                                    'options'  => [
                                        'Local',
                                        'Development',
                                        'Staging',
                                        'UAT',
                                        'Production',
                                    ],
                                    'required' => true,
                                ],

                                [
                                    'name'     => 'version',
                                    'label'    => 'Target Version / Release',
                                    'type'     => 'text',
                                    'required' => false,
                                ],

                                [
                                    'name'     => 'sprint',
                                    'label'    => 'Sprint',
                                    'type'     => 'text',
                                    'required' => false,
                                ],

                                [
                                    'name'     => 'story_points',
                                    'label'    => 'Story Points',
                                    'type'     => 'number',
                                    'required' => false,
                                ],

                                [
                                    'name'     => 'code_review_required',
                                    'label'    => 'Code Review Required',
                                    'type'     => 'checkbox',
                                    'required' => false,
                                ],

                                [
                                    'name'     => 'qa_required',
                                    'label'    => 'QA / Testing Required',
                                    'type'     => 'checkbox',
                                    'required' => false,
                                ],

                                [
                                    'name'     => 'deployment_required',
                                    'label'    => 'Deployment Required',
                                    'type'     => 'checkbox',
                                    'required' => false,
                                ],
                            ],
                        ],

                        [
                            'title'  => 'Acceptance Criteria',
                            'fields' => [

                                [
                                    'name'     => 'acceptance_criteria',
                                    'label'    => 'Acceptance Criteria',
                                    'type'     => 'textarea',
                                    'required' => false,
                                ],

                                [
                                    'name'     => 'technical_notes',
                                    'label'    => 'Technical Notes',
                                    'type'     => 'textarea',
                                    'required' => false,
                                ],
                            ],
                        ],
                    ],

                    'line_items' => [
                        'enabled' => true,
                        'label'   => 'Development Tasks',
                        'types'   => [
                            'service',
                            'labor',
                        ],
                    ],
                ],
            ],

            /*
            |--------------------------------------------------------------------------
            | 3. IT Support / Help Desk
            |--------------------------------------------------------------------------
            */
            [
                'name'        => 'IT Support / Help Desk',
                'slug'        => 'it-support',
                'description' => 'Handle technical support requests, troubleshooting, user issues and IT incidents.',
                'config'      => [
                    'fields' => [

                        'title' => [
                            'show'     => true,
                            'required' => true,
                        ],

                        'description' => [
                            'show'     => true,
                            'required' => true,
                        ],

                        'customer_id' => [
                            'show'     => true,
                            'required' => false,
                        ],

                        'vendor_id' => [
                            'show'     => false,
                            'required' => false,
                        ],

                        'assigned_to' => [
                            'show'     => true,
                            'required' => true,
                            'label'    => 'Support Engineer',
                        ],

                        'warehouse_id' => [
                            'show'     => false,
                            'required' => false,
                        ],

                        'priority' => [
                            'show'     => true,
                            'required' => true,
                        ],

                        'due_at' => [
                            'show'     => true,
                            'required' => false,
                            'label'    => 'Resolution Deadline',
                        ],

                        'estimated_cost' => [
                            'show'     => true,
                            'required' => false,
                        ],

                        'estimated_hours' => [
                            'show'     => true,
                            'required' => false,
                        ],

                        'reference_no' => [
                            'show'     => true,
                            'required' => false,
                            'label'    => 'Ticket / Incident No.',
                        ],
                    ],

                    'sections' => [

                        [
                            'title'  => 'Incident Details',
                            'fields' => [

                                [
                                    'name'     => 'issue_category',
                                    'label'    => 'Issue Category',
                                    'type'     => 'select',
                                    'options'  => [
                                        'Hardware',
                                        'Software',
                                        'Network',
                                        'Database',
                                        'Email',
                                        'Security',
                                        'Access / Permission',
                                        'Other',
                                    ],
                                    'required' => true,
                                ],

                                [
                                    'name'     => 'severity',
                                    'label'    => 'Severity',
                                    'type'     => 'select',
                                    'options'  => [
                                        'Low',
                                        'Medium',
                                        'High',
                                        'Critical',
                                    ],
                                    'required' => true,
                                ],

                                [
                                    'name'     => 'affected_system',
                                    'label'    => 'Affected System',
                                    'type'     => 'text',
                                    'required' => false,
                                ],

                                [
                                    'name'     => 'affected_users',
                                    'label'    => 'Affected Users',
                                    'type'     => 'number',
                                    'required' => false,
                                ],
                            ],
                        ],

                        [
                            'title'  => 'Resolution',
                            'fields' => [

                                [
                                    'name'     => 'resolution_required',
                                    'label'    => 'Required Resolution',
                                    'type'     => 'textarea',
                                    'required' => false,
                                ],

                                [
                                    'name'     => 'resolution_notes',
                                    'label'    => 'Resolution Notes',
                                    'type'     => 'textarea',
                                    'required' => false,
                                ],
                            ],
                        ],
                    ],

                    'line_items' => [
                        'enabled' => true,
                        'label'   => 'Support Services / Parts',
                        'types'   => [
                            'service',
                            'product',
                            'labor',
                        ],
                    ],
                ],
            ],

            /*
            |--------------------------------------------------------------------------
            | 4. Bug Fix / Maintenance
            |--------------------------------------------------------------------------
            */
            [
                'name'        => 'Bug Fix / Maintenance',
                'slug'        => 'bug-fix',
                'description' => 'Resolve software bugs, defects, performance problems and maintenance tasks.',
                'config'      => [
                    'fields' => [

                        'title' => [
                            'show'     => true,
                            'required' => true,
                        ],

                        'description' => [
                            'show'     => true,
                            'required' => true,
                        ],

                        'customer_id' => [
                            'show'     => true,
                            'required' => false,
                        ],

                        'vendor_id' => [
                            'show'     => false,
                            'required' => false,
                        ],

                        'assigned_to' => [
                            'show'     => true,
                            'required' => true,
                            'label'    => 'Developer',
                        ],

                        'warehouse_id' => [
                            'show'     => false,
                            'required' => false,
                        ],

                        'priority' => [
                            'show'     => true,
                            'required' => true,
                        ],

                        'due_at' => [
                            'show'     => true,
                            'required' => true,
                        ],

                        'estimated_cost' => [
                            'show'     => true,
                            'required' => false,
                        ],

                        'estimated_hours' => [
                            'show'     => true,
                            'required' => true,
                        ],

                        'reference_no' => [
                            'show'     => true,
                            'required' => false,
                            'label'    => 'Bug / Ticket No.',
                        ],
                    ],

                    'sections' => [

                        [
                            'title'  => 'Bug Information',
                            'fields' => [

                                [
                                    'name'     => 'project_name',
                                    'label'    => 'Project / Product',
                                    'type'     => 'text',
                                    'required' => true,
                                ],

                                [
                                    'name'     => 'module_name',
                                    'label'    => 'Affected Module',
                                    'type'     => 'text',
                                    'required' => true,
                                ],

                                [
                                    'name'     => 'bug_type',
                                    'label'    => 'Bug Type',
                                    'type'     => 'select',
                                    'options'  => [
                                        'Functional',
                                        'UI / UX',
                                        'Performance',
                                        'Security',
                                        'Database',
                                        'Integration',
                                        'Compatibility',
                                    ],
                                    'required' => true,
                                ],

                                [
                                    'name'     => 'environment',
                                    'label'    => 'Environment',
                                    'type'     => 'select',
                                    'options'  => [
                                        'Development',
                                        'Staging',
                                        'UAT',
                                        'Production',
                                    ],
                                    'required' => true,
                                ],
                            ],
                        ],

                        [
                            'title'  => 'Technical Information',
                            'fields' => [

                                [
                                    'name'     => 'current_version',
                                    'label'    => 'Current Version',
                                    'type'     => 'text',
                                    'required' => false,
                                ],

                                [
                                    'name'     => 'technology_stack',
                                    'label'    => 'Technology Stack',
                                    'type'     => 'text',
                                    'required' => false,
                                ],

                                [
                                    'name'     => 'reproduction_steps',
                                    'label'    => 'Steps to Reproduce',
                                    'type'     => 'textarea',
                                    'required' => false,
                                ],

                                [
                                    'name'     => 'expected_result',
                                    'label'    => 'Expected Result',
                                    'type'     => 'textarea',
                                    'required' => false,
                                ],

                                [
                                    'name'     => 'actual_result',
                                    'label'    => 'Actual Result',
                                    'type'     => 'textarea',
                                    'required' => false,
                                ],
                            ],
                        ],
                    ],

                    'line_items' => [
                        'enabled' => true,
                        'label'   => 'Development / Maintenance Tasks',
                        'types'   => [
                            'service',
                            'labor',
                        ],
                    ],
                ],
            ],

            /*
            |--------------------------------------------------------------------------
            | 5. Deployment / DevOps
            |--------------------------------------------------------------------------
            */
            [
                'name'        => 'Deployment / DevOps',
                'slug'        => 'deployment',
                'description' => 'Application deployment, server configuration, CI/CD, infrastructure and DevOps operations.',
                'config'      => [
                    'fields' => [

                        'title' => [
                            'show'     => true,
                            'required' => true,
                        ],

                        'description' => [
                            'show'     => true,
                            'required' => true,
                        ],

                        'customer_id' => [
                            'show'     => true,
                            'required' => false,
                        ],

                        'vendor_id' => [
                            'show'     => false,
                            'required' => false,
                        ],

                        'assigned_to' => [
                            'show'     => true,
                            'required' => true,
                            'label'    => 'DevOps Engineer',
                        ],

                        'warehouse_id' => [
                            'show'     => false,
                            'required' => false,
                        ],

                        'priority' => [
                            'show'     => true,
                            'required' => true,
                        ],

                        'due_at' => [
                            'show'     => true,
                            'required' => true,
                            'label'    => 'Deployment Date',
                        ],

                        'estimated_cost' => [
                            'show'     => true,
                            'required' => false,
                        ],

                        'estimated_hours' => [
                            'show'     => true,
                            'required' => true,
                        ],

                        'reference_no' => [
                            'show'     => true,
                            'required' => false,
                            'label'    => 'Release / Deployment Ref',
                        ],
                    ],

                    'sections' => [

                        [
                            'title'  => 'Deployment Details',
                            'fields' => [

                                [
                                    'name'     => 'project_name',
                                    'label'    => 'Project / Application',
                                    'type'     => 'text',
                                    'required' => true,
                                ],

                                [
                                    'name'     => 'deployment_environment',
                                    'label'    => 'Target Environment',
                                    'type'     => 'select',
                                    'options'  => [
                                        'Development',
                                        'Staging',
                                        'UAT',
                                        'Production',
                                    ],
                                    'required' => true,
                                ],

                                [
                                    'name'     => 'version',
                                    'label'    => 'Version / Release',
                                    'type'     => 'text',
                                    'required' => true,
                                ],

                                [
                                    'name'     => 'server',
                                    'label'    => 'Server / Infrastructure',
                                    'type'     => 'text',
                                    'required' => false,
                                ],

                                [
                                    'name'     => 'deployment_method',
                                    'label'    => 'Deployment Method',
                                    'type'     => 'select',
                                    'options'  => [
                                        'Manual',
                                        'CI/CD',
                                        'Docker',
                                        'GitHub Actions',
                                        'GitLab CI',
                                        'Other',
                                    ],
                                    'required' => false,
                                ],
                            ],
                        ],

                        [
                            'title'  => 'Deployment Checklist',
                            'fields' => [

                                [
                                    'name'     => 'backup_required',
                                    'label'    => 'Backup Required',
                                    'type'     => 'checkbox',
                                    'required' => false,
                                ],

                                [
                                    'name'     => 'database_migration',
                                    'label'    => 'Database Migration Required',
                                    'type'     => 'checkbox',
                                    'required' => false,
                                ],

                                [
                                    'name'     => 'rollback_plan',
                                    'label'    => 'Rollback Plan',
                                    'type'     => 'textarea',
                                    'required' => false,
                                ],
                            ],
                        ],
                    ],

                    'line_items' => [
                        'enabled' => true,
                        'label'   => 'DevOps / Deployment Tasks',
                        'types'   => [
                            'service',
                            'labor',
                        ],
                    ],
                ],
            ],

            /*
            |--------------------------------------------------------------------------
            | 6. Hardware Purchase / Procurement
            |--------------------------------------------------------------------------
            */
            [
                'name'        => 'Hardware Purchase / Procurement',
                'slug'        => 'procurement',
                'description' => 'Purchase hardware, software licenses, tools, equipment and other business assets.',
                'config'      => [
                    'fields' => [

                        'title' => [
                            'show'     => true,
                            'required' => true,
                        ],

                        'description' => [
                            'show'     => true,
                            'required' => false,
                        ],

                        'customer_id' => [
                            'show'     => false,
                            'required' => false,
                        ],

                        'vendor_id' => [
                            'show'     => true,
                            'required' => true,
                        ],

                        'assigned_to' => [
                            'show'     => true,
                            'required' => false,
                            'label'    => 'Requested By',
                        ],

                        'warehouse_id' => [
                            'show'     => true,
                            'required' => false,
                            'label'    => 'Delivery Location',
                        ],

                        'priority' => [
                            'show'     => true,
                            'required' => false,
                        ],

                        'due_at' => [
                            'show'     => true,
                            'required' => true,
                            'label'    => 'Required By',
                        ],

                        'estimated_cost' => [
                            'show'     => true,
                            'required' => true,
                            'label'    => 'Estimated Budget',
                        ],

                        'estimated_hours' => [
                            'show'     => false,
                            'required' => false,
                        ],

                        'reference_no' => [
                            'show'     => true,
                            'required' => false,
                            'label'    => 'PR / Budget Ref',
                        ],
                    ],

                    'sections' => [

                        [
                            'title'  => 'Procurement Details',
                            'fields' => [

                                [
                                    'name'     => 'procurement_category',
                                    'label'    => 'Category',
                                    'type'     => 'select',
                                    'options'  => [
                                        'Computer',
                                        'Laptop',
                                        'Server',
                                        'Networking',
                                        'Software License',
                                        'Office Equipment',
                                        'Other',
                                    ],
                                    'required' => true,
                                ],

                                [
                                    'name'     => 'delivery_address',
                                    'label'    => 'Delivery Address',
                                    'type'     => 'textarea',
                                    'required' => false,
                                ],

                                [
                                    'name'     => 'payment_terms',
                                    'label'    => 'Payment Terms',
                                    'type'     => 'text',
                                    'required' => false,
                                ],

                                [
                                    'name'     => 'budget_code',
                                    'label'    => 'Budget Code',
                                    'type'     => 'text',
                                    'required' => false,
                                ],
                            ],
                        ],
                    ],

                    'line_items' => [
                        'enabled' => true,
                        'label'   => 'Items to Purchase',
                        'types'   => [
                            'product',
                            'expense',
                        ],
                    ],
                ],
            ],

            /*
            |--------------------------------------------------------------------------
            | 7. Repair / Maintenance
            |--------------------------------------------------------------------------
            */
            [
                'name'        => 'Repair / Maintenance',
                'slug'        => 'maintenance',
                'description' => 'Equipment repair, preventive maintenance and technical servicing.',
                'config'      => [
                    'fields' => [

                        'title' => [
                            'show'     => true,
                            'required' => true,
                        ],

                        'description' => [
                            'show'     => true,
                            'required' => true,
                        ],

                        'customer_id' => [
                            'show'     => true,
                            'required' => false,
                        ],

                        'vendor_id' => [
                            'show'     => false,
                            'required' => false,
                        ],

                        'assigned_to' => [
                            'show'     => true,
                            'required' => true,
                        ],

                        'warehouse_id' => [
                            'show'     => true,
                            'required' => false,
                            'label'    => 'Site / Location',
                        ],

                        'priority' => [
                            'show'     => true,
                            'required' => true,
                        ],

                        'due_at' => [
                            'show'     => true,
                            'required' => true,
                        ],

                        'estimated_cost' => [
                            'show'     => true,
                            'required' => false,
                        ],

                        'estimated_hours' => [
                            'show'     => true,
                            'required' => false,
                        ],

                        'reference_no' => [
                            'show'     => true,
                            'required' => false,
                            'label'    => 'Asset / Service Ref',
                        ],
                    ],

                    'sections' => [

                        [
                            'title'  => 'Asset Information',
                            'fields' => [

                                [
                                    'name'     => 'asset_name',
                                    'label'    => 'Equipment / Asset Name',
                                    'type'     => 'text',
                                    'required' => true,
                                ],

                                [
                                    'name'     => 'asset_code',
                                    'label'    => 'Asset Code',
                                    'type'     => 'text',
                                    'required' => false,
                                ],

                                [
                                    'name'     => 'serial_number',
                                    'label'    => 'Serial Number',
                                    'type'     => 'text',
                                    'required' => false,
                                ],

                                [
                                    'name'     => 'warranty_status',
                                    'label'    => 'Warranty Status',
                                    'type'     => 'select',
                                    'options'  => [
                                        'Under Warranty',
                                        'Expired',
                                        'Unknown',
                                    ],
                                    'required' => false,
                                ],

                                [
                                    'name'     => 'maintenance_type',
                                    'label'    => 'Maintenance Type',
                                    'type'     => 'select',
                                    'options'  => [
                                        'Preventive',
                                        'Corrective',
                                        'Emergency',
                                        'Routine Service',
                                    ],
                                    'required' => true,
                                ],
                            ],
                        ],

                        [
                            'title'  => 'Maintenance Details',
                            'fields' => [

                                [
                                    'name'     => 'problem_description',
                                    'label'    => 'Problem Description',
                                    'type'     => 'textarea',
                                    'required' => false,
                                ],

                                [
                                    'name'     => 'required_action',
                                    'label'    => 'Required Action',
                                    'type'     => 'textarea',
                                    'required' => false,
                                ],
                            ],
                        ],
                    ],

                    'line_items' => [
                        'enabled' => true,
                        'label'   => 'Parts & Labor',
                        'types'   => [
                            'product',
                            'labor',
                        ],
                    ],
                ],
            ],
        ];

        /*
        |--------------------------------------------------------------------------
        | Seed Global Work Order Types
        |--------------------------------------------------------------------------
        |
        | Work Order Types are system-level definitions.
        | They are NOT duplicated for every business.
        |
        */
        foreach ($types as $index => $type) {

            WorkOrderType::updateOrCreate(
                [
                    'slug' => $type['slug'],
                ],
                [
                    'name'        => $type['name'],
                    'description' => $type['description'],
                    'config'      => $type['config'],
                    'sort_order'  => $index + 1,
                    'is_active'   => true,
                ]
            );

            $this->command->info(
                "Work order type seeded: {$type['name']}"
            );
        }
    }
}