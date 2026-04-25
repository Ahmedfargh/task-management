<?php

namespace App\Services;

use App\Models\Task;
use App\Models\User;
use App\Repositories\Contracts\TaskRepositoryInterface;
use App\Jobs\LogTaskActivity;
use Illuminate\Support\Facades\Cache;
use Illuminate\Pagination\LengthAwarePaginator;

class TaskService
{
    public function __construct(
        protected TaskRepositoryInterface $taskRepository
    ) {}

    public function getTasksForUser(User $user, array $filters): LengthAwarePaginator
    {
        $tenantId = $user->tenant_id;
        $page = $filters['page'] ?? 1;
        $status = $filters['status'] ?? '';
        $search = $filters['title'] ?? '';

        $cacheKey = "tasks_p{$page}_s{$status}_q{$search}";

        return Cache::tags(["tenant_{$tenantId}"])->remember($cacheKey, 600, function () use ($filters) {
            return $this->taskRepository->getAll($filters);
        });
    }

    public function createTask(User $user, array $data): Task
    {
        $data['user_id'] = $user->id;
        $data['tenant_id'] = $user->tenant_id;

        $task = $this->taskRepository->create($data);

        $this->clearTenantCache($user->tenant_id);
        LogTaskActivity::dispatch('created', $task);

        return $task;
    }

    public function updateTask(User $user, Task $task, array $data): Task
    {
        $updatedTask = $this->taskRepository->update($task, $data);

        $this->clearTenantCache($user->tenant_id);
        LogTaskActivity::dispatch('updated', $updatedTask);

        return $updatedTask;
    }

    public function deleteTask(User $user, Task $task): bool
    {
        $deleted = $this->taskRepository->delete($task);

        $this->clearTenantCache($user->tenant_id);
        LogTaskActivity::dispatch('deleted', $task);

        return $deleted;
    }

    protected function clearTenantCache(string $tenantId): void
    {
        Cache::tags(["tenant_{$tenantId}"])->flush();
    }
}
