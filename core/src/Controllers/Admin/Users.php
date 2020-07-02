<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Psr\Http\Message\ResponseInterface;
use Vesp\Controllers\ModelController;

class Users extends ModelController
{
    protected $model = User::class;
    protected $scope = 'users';

    /**
     * @return ResponseInterface
     */
    public function patch()
    {
        if (!$password = $this->getProperty('password')) {
            $this->unsetProperty('password');
        }

        return parent::patch();
    }

    /**
     * @param User $record
     * @return bool|string
     */
    protected function beforeSave($record)
    {
        if (!$record->username) {
            return 'You should specify an username';
        }
        if (!$record->password) {
            return 'You should specify a password';
        }
        if (!$record->fullname) {
            return 'You should specify a full name';
        }
        if (!$record->role_id) {
            return 'You should specify an user group';
        }

        $check = User::query()->where('username', '=', $record->username);
        if ($record->exists) {
            $check->where('id', '!=', $record->id);
        }
        if ($check->count()) {
            return 'This username is already in use';
        }

        return true;
    }

    /**
     * @param Builder $c
     * @return Builder
     */
    protected function beforeCount($c)
    {
//        if ($query = trim($this->getProperty('query'))) {
//            $c->where(
//                function (Builder $q) use ($query) {
//                    $q->where('username', 'LIKE', "%$query%");
//                    $q->orWhere('fullname', 'LIKE', "%$query%");
//                }
//            );
//        }

        return $c;
    }

    /**
     * @param Builder $c
     * @return Builder
     */
    protected function afterCount($c)
    {
        $c->select('id', 'role_id', 'username', 'fullname', 'email', 'phone', 'telegram', 'active');
        $c->with('role:id,title');

        return $c;
    }
}