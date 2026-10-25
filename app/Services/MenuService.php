<?php

namespace App\Services;

use App\Models\Permission;
use App\Models\RolePermission;
use App\Models\Tp_option;
use Carbon\Carbon;
use Illuminate\Support\Facades\Route;


class MenuService
{
     /** Routes that should never appear in the sidebar. */
    private const EXCLUDED_NAMES = ['Land Plot', 'Member', 'Deposit Payment', 'Online Payment Setting'];

    /** Sections granted to a business without a package. */
    private const DEFAULT_SECTIONS = ['inventory', 'hr-payroll', 'accounts', 'dealer', 'work_order', 'production', 'general', 'crm'];

    private $user;
    private $business;
    private array $sections = [];
    private bool $freeExpired = false;
    private bool $packExpired = false;

    public function __construct($user)
    {
        $this->user = $user;
        $this->business = $user->business;
        $this->resolveAccess();
    }

    /** Works out which sections the user can see, and whether trial or package has ended. */
    private function resolveAccess(): void
    {
        if (!$this->business) {
            $this->freeExpired = true;
            return;
        }

        if ($this->business->package) {
            $this->packExpired = Carbon::now()->gt($this->business->pack_end_date);

            $sections = json_decode($this->business->package->pack_option, true);
            $this->sections = is_array($sections) ? $sections : [];
        } else {
            $days = 0;
            $option = Tp_option::where('option_name', 'user_limit')->first();
            if ($option) {
                $days = (int) (json_decode($option->option_value)->days ?? 0);
            }

            $trialEnd = Carbon::parse($this->business->start_date)->addDays($days);
            $this->freeExpired = Carbon::now()->gt($trialEnd);
            $this->sections = self::DEFAULT_SECTIONS;
        }

        // Business type 0 is the admin-type account; the others use the same default list.
        $this->sections[] = 'general';
    }

    public function isBlocked(): bool
    {
        return $this->freeExpired || $this->packExpired;
    }

    public function isAdmin(): bool
    {
        return (int) $this->user->user_type === 0;
    }

    public function businessTypeId(): ?int
    {
        return $this->business?->business_type_id;
    }

    /** Captions (top-level groups) for the admin branch. */
    public function adminCaptions()
    {
        return Permission::where('is_caption', '!=', 0)
            ->orderBy('menu_sort')
            ->get();
    }

    /** Captions for the role-based branch. */
    public function roleCaptions()
    {
        return RolePermission::join('permissions', 'permissions.id', 'role_permissions.permission_id')
            ->where('role_permissions.role_id', $this->user->role_id)
            ->where('permissions.is_caption', '!=', 0)
            ->orderBy('permissions.menu_sort')
            ->select('permissions.*')
            ->get();
    }

    /** Child menus under a parent, for the admin branch, filtered by section. */
    public function adminMenus(int $parentId)
    {
        return Permission::whereIn('section', $this->sections)
            ->whereNotIn('name', self::EXCLUDED_NAMES)
            ->where('parent_id', $parentId)
            ->orderBy('menu_sort')
            ->get();
    }

    /** Child menus under a parent, for the role-based branch. */
    public function roleMenus(int $parentId)
    {
        return RolePermission::join('permissions', 'permissions.id', 'role_permissions.permission_id')
            ->where('role_permissions.role_id', $this->user->role_id)
            ->where('permissions.parent_id', $parentId)
            ->whereNotIn('permissions.name', self::EXCLUDED_NAMES)
            ->orderBy('permissions.menu_sort')
            ->select('permissions.*')
            ->get();
    }

    /** Whether a menu item should be shown given its is_condition rules (admin branch). */
    public function passesCondition($menu): bool
    {
        if ($menu->is_condition == 1) {
            $hasSection = in_array($menu->condition_type, $this->sections, true);
            return $menu->condition_val == 0 ? !$hasSection : $hasSection;
        }

        if ($menu->is_condition == 2) {
            return $this->businessTypeId() != $menu->condition_val;
        }

        return true;
    }

    /** Whether a menu item is limited to a specific business type. */
    public function passesBusinessType($menu): bool
    {
        $restrictions = [
            'p_type.index'    => 5,
            'generic.index'   => 5,
            'territory.index' => 15,
            'road.index'      => 15,
        ];

        if (!isset($restrictions[$menu->route_name])) {
            return true;
        }

        return $this->businessTypeId() === $restrictions[$menu->route_name];
    }

    /** True when the current route matches this item or one of its children. */
    public function isActive($menu): bool
    {
        $current = Route::currentRouteName();

        if ($menu->route_name && $current === $menu->route_name) {
            return true;
        }

        return collect($menu->child_menus ?? [])
            ->contains(fn ($child) => $child->route_name === $current);
    }

    /** Whether the menu item has children that should render as a collapsible group. */
    public function isGroup($menu): bool
    {
        return (int) $menu->parent_menu === 1 && empty($menu->route_name);
    }

    public function sections(): array
    {
        return $this->sections;
    }
}