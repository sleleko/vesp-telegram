<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Models\UserRole;
use Illuminate\Database\Eloquent\Builder;
use Vesp\Controllers\ModelController;

class UserRoles extends ModelController
{
    protected $model = UserRole::class;
    protected $scope = 'users';

    /**
     * @param UserRole $record
     * @return bool|string
     */
    protected function beforeSave($record)
    {
        if (!$record->title) {
            return 'You should specify a title';
        }

        $check = UserRole::query()->where('title', '=', $record->title);
        if ($record->exists) {
            $check->where('id', '!=', $record->id);
        }
        if ($check->count()) {
            return 'This title is already in use';
        }

        $scope = $this->getProperty('scope');
        if (is_string($scope)) {
            $record->scope = array_map('trim', explode(',', $scope));
        }

        return true;
    }


    /**
     * @param Builder $c
     *
     * @return Builder
     */
    protected function beforeCount($c)
    {
//        if ($query = trim($this->getProperty('query'))) {
//            $c->where('title', 'LIKE', "%$query%");
//        }

        return $c;
    }


    /**
     * @param Builder $c
     *
     * @return Builder
     */
    protected function afterCount($c)
    {
        $c->select('id', 'title', 'scope');
        $c->withCount('users');

        return $c;
    }
}