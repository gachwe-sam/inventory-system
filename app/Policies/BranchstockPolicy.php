<?php

namespace App\Policies;

use App\Models\Branch;
use App\Models\Branchstock;
use App\Models\User;
use App\Support\StockPermissions;

class BranchstockPolicy
{
    public function view(User $user, Branchstock $stock): bool
    {
        return $this->sameBranch($user, $stock->branch_id);
    }

    public function update(User $user, Branchstock $stock): bool
    {
        return $this->sameBranch($user, $stock->branch_id);
    }

    public function receive(User $user, Branchstock $stock): bool
    {
        return $this->sameBranch($user, $stock->branch_id) && $user->can(StockPermissions::RECEIVE);
    }

    public function issue(User $user, Branchstock $stock): bool
    {
        return $this->sameBranch($user, $stock->branch_id) && $user->can(StockPermissions::ISSUE);
    }

    public function transfer(User $user, Branchstock $stock): bool
    {
        return $this->sameBranch($user, $stock->branch_id) && $user->can(StockPermissions::TRANSFER);
    }

    public function create(User $user, Branch $branch): bool
    {
        return $this->sameBranch($user, $branch->id) && $user->can(StockPermissions::CREATE);
    }

    private function sameBranch(User $user, int $branchId): bool
    {
        return $user->branch_id !== null && (int) $user->branch_id === $branchId;
    }
}
