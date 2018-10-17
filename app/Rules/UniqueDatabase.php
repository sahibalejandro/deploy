<?php

namespace App\Rules;

use PDO;
use Illuminate\Contracts\Validation\Rule;

class UniqueDatabase implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $db = config('database.connections.mysql');
        $pdo = new PDO("mysql:host={$db['host']}", $db['username'], $db['password']);
        $rows = $pdo->query('SHOW DATABASES;')->fetchAll(PDO::FETCH_OBJ);

        foreach ($rows as $row) {
            if ($row->Database === $value) {
                return false;
            }
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'A database with the same name already exists.';
    }
}
