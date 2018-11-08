# Upgrade from 0.0.7 to 1.0.0

In version 1.0.0 recovery codes are hashed in database. 

Copy code bellow to migrations folder and run migrations:

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Lifeonscreen\Google2fa\Models\User2fa;

class HashGoogle2faRecoveryCodes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     * @throws Exception
     */
    public function up()
    {
        DB::beginTransaction();
        $users2fa = User2fa::all();
        foreach ($users2fa as $user2fa) {
            $recoveryCodes = json_decode($user2fa->recovery);
            array_walk($recoveryCodes, function (&$value) {
                $value = password_hash($value, config('lifeonscreen2fa.recovery_codes.hashing_algorithm'));
            });
            $user2fa->recovery = json_encode($recoveryCodes);
            $user2fa->save();
        }
        DB::commit();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
}
```

