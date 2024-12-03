<?php

use Illuminate\Support\Facades\DB;

if (!function_exists('getDropdownList')) {
    /**
     * Generate dropdown list from database.
     *
     * @param string $table
     * @param array $columns
     * @return array
     */
    function getDropdownList($table, $columns)
    {
        $query = DB::table($table)->select($columns)->get();

        if ($query->count() >= 1) {
            $option1 = ['' => '- Pilih -'];
            $option2 = $query->pluck($columns[1], $columns[0])->toArray();
            $options = $option1 + $option2;
            return $options;
        }

        return ['' => '- Pilih -'];
    }
}
