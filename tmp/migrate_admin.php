<?php

use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

$oldId = 1;
$newId = 2;

echo "Starting migration from ID $oldId to ID $newId...\n";

// Tables to update
$tables = ['posts', 'certificates', 'educations', 'languages', 'activity_logs', 'analytics'];

foreach ($tables as $table) {
    if (Schema::hasTable($table) && Schema::hasColumn($table, 'user_id')) {
        $count = DB::table($table)->where('user_id', $oldId)->count();
        if ($count > 0) {
            DB::table($table)->where('user_id', $oldId)->update(['user_id' => $newId]);
            echo "Migrated $count records from $table\n";
        }
    }
}

// Special case: Copy customization data if user 2 has nulls
$u1 = User::find($oldId);
$u2 = User::find($newId);

if ($u1 && $u2) {
    $fields = ['contact_title', 'contact_subtitle', 'about_grc_list', 'about_tech_list', 'phone', 'address', 'website', 'summary', 'avatar'];
    foreach ($fields as $field) {
        if (is_null($u2->$field) || empty($u2->$field)) {
            $u2->$field = $u1->$field;
        }
    }
    $u2->save();
    echo "Synchronized profile data to User 2\n";
    
    // Delete old user
    $u1->delete();
    echo "Deleted old admin account ($u1->email)\n";
} else {
    echo "Could not find one or both users.\n";
}

Cache::forget('portfolio_owner');
echo "Cache cleared. Migration complete.\n";
