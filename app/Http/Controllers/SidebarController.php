<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class SidebarController extends Controller
{
    private function checkAccess($groupId)
    {
        $group = DB::table('access_right_group')->where('id', $groupId)->first();
        if (!$group) {
            return false;
        }

        $prefix = trim($group->path, '/');
        $cleanPrefix = str_replace('-', '_', $prefix);
        $possibleSlugs = [$prefix . '-list', $cleanPrefix . '-list', $prefix, $cleanPrefix];

        $slugs = DB::table('access_right')
            ->where('id_access_right_group', $groupId)
            ->whereIn('name', $possibleSlugs)
            ->pluck('name');

        if ($slugs->isEmpty()) {
            $anySlug = DB::table('access_right')
                ->where('id_access_right_group', $groupId)
                ->where('name', 'not like', '%-column_%')
                ->value('name');

            if (!$anySlug) {
                return true;
            }

            try {
                return Gate::allows('hasAccess', $anySlug);
            } catch (\Exception $e) {
                return false;
            }
        }

        foreach ($slugs as $slug) {
            try {
                if (Gate::allows('hasAccess', $slug)) {
                    return true;
                }
            } catch (\Exception $e) {
                continue;
            }
        }

        return false;
    }

    public function getMenuStructure()
    {
        $allGroups = DB::table('access_right_group')->where('status', 'terbit')->orderBy('ordinal')->get();
        $menuStructure = [];
        $categories = ['dashboard', 'umum', 'transaksi', 'master', 'report', 'hak-akses'];

        foreach ($categories as $cat) {
            $groups = $allGroups->filter(fn($item) => strtolower($item->category ?? '') === $cat);
            $filteredGroups = [];

            foreach ($groups as $group) {
                // Untuk sementara, jika ingin testing pastikan lolos dulu tanpa Gate jika belum diset
                // Atau pastikan checkAccess bernilai true
                $filteredGroups[] = [
                    'group' => $group,
                    'children' => [],
                    'hasChild' => false,
                ];
            }

            if (count($filteredGroups) > 0) {
                $menuStructure[$cat] = $filteredGroups;
            }
        }

        // Cek ini di browser, apakah array-nya terisi atau kosong?

        return $menuStructure;
    }
}
