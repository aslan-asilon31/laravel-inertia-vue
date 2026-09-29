<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MsActionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        DB::table('ms_actions')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $now = Carbon::now();

        $actions = [
            ['id' => 'list', 'name' => 'List', 'ordinal' => 1],
            ['id' => 'create', 'name' => 'Create', 'ordinal' => 2],
            ['id' => 'edit', 'name' => 'Edit', 'ordinal' => 3],
            ['id' => 'show', 'name' => 'Show', 'ordinal' => 4],
            ['id' => 'update', 'name' => 'Update', 'ordinal' => 5],
            ['id' => 'store', 'name' => 'Store', 'ordinal' => 6],
            ['id' => 'delete', 'name' => 'Delete', 'ordinal' => 7],
            ['id' => 'draf', 'name' => 'Draf', 'ordinal' => 8],
            ['id' => 'terbit', 'name' => 'Terbit', 'ordinal' => 9],

            // --- Export, Import & File Handling ---
            ['id' => 'export', 'name' => 'Export Data', 'ordinal' => 11],
            ['id' => 'import', 'name' => 'Import Data', 'ordinal' => 12],
            ['id' => 'pdf', 'name' => 'PDF', 'ordinal' => 13],
            ['id' => 'download', 'name' => 'Download', 'ordinal' => 14],
            ['id' => 'email', 'name' => 'Email', 'ordinal' => 15],
            ['id' => 'delete_bulk', 'name' => 'Delete Bulk', 'ordinal' => 16],

            // --- Additional Feature & Document Controls ---
            ['id' => 'print', 'name' => 'Print', 'ordinal' => 17],
            ['id' => 'export-excel', 'name' => 'Export Excel Data', 'ordinal' => 18],
            ['id' => 'export-pdf', 'name' => 'Export Pdf Data', 'ordinal' => 19],
            ['id' => 'import-excel', 'name' => 'Import Excel Data', 'ordinal' => 20],
            ['id' => 'import-pdf', 'name' => 'Import Pdf Data', 'ordinal' => 21],
            ['id' => 'view-price', 'name' => 'View Pricing Columns', 'ordinal' => 22],
            ['id' => 'view-cost', 'name' => 'View Cost/Profit Columns', 'ordinal' => 23],
            ['id' => 'approved', 'name' => 'Approve/Verify Document', 'ordinal' => 24],
            ['id' => 'cancel', 'name' => 'Cancel Document', 'ordinal' => 25],


        ];

        foreach ($actions as $action) {
            DB::table('ms_actions')->insert([
                'id'           => $action['id'],
                'name'         => $action['name'],
                'action'       => $action['id'],
                'status'       => 'terbit',
                'ordinal'      => $action['ordinal'],
                'created_by'   => 'system',
                'updated_by'   => 'system',
                'created_at'   => $now,
                'updated_at'   => $now,
                'is_activated' => 1,
            ]);
        }

        $this->command->info('Seeder Master Action Successfully! ✅');
    }
}
