<?php

namespace App\Services;

use App\Repositories\UserDao;
use App\Business\UserBo;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

class UserService
{
    protected $dao;
    protected $bo;

    // cache keys
    protected const CACHE_KEY_ALL = 'users:all';
    protected const CACHE_KEY_USER = 'users:%d';
    protected const CACHE_TTL = 60 * 60; // 1 hour (adjust)

    public function __construct(UserDao $dao, UserBo $bo)
    {
        $this->dao = $dao;
        $this->bo = $bo;
    }

    public function create(array $data): User
    {
        $data = $this->bo->prepareForCreate($data);
        $user = $this->dao->create($data);

        // invalidate caches
        $this->invalidateCaches($user->id);

        return $user;
    }

    public function update(User $user, array $data): User
    {
        $data = $this->bo->prepareForUpdate($user, $data);
        $user = $this->dao->update($user, $data);

        // invalidate caches
        $this->invalidateCaches($user->id);

        return $user;
    }

    public function findById(int $id): ?User
    {
        $cacheKey = sprintf(self::CACHE_KEY_USER, $id);

        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($id) {
            return $this->dao->findById($id);
        });
    }

    public function all()
    {
        return Cache::remember(self::CACHE_KEY_ALL, self::CACHE_TTL, function () {
            return $this->dao->all();
        });
    }

    protected function invalidateCaches(int $id = null)
    {
        // Invalidate list cache
        Cache::forget(self::CACHE_KEY_ALL);

        // Invalidate single user cache
        if ($id) {
            Cache::forget(sprintf(self::CACHE_KEY_USER, $id));
        }

        // If your cache driver supports tags (redis/memcached) you could use tags to clear multiple keys.
    }
}
