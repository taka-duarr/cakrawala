<?php

use App\Models\User;

$users = User::with('role')->get();
foreach ($users as $u) {
    echo $u->email . ' | role: ' . ($u->role ? $u->role->name : 'NO ROLE') . ' | verified: ' . ($u->email_verified_at ? 'YES' : 'NOT VERIFIED') . PHP_EOL;
}
