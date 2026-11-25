<?php

namespace App\Support;

use App\Models\Trip;
use App\Models\User;
use Illuminate\Support\Facades\Route;

class Menu
{
    /** Các nhóm ô chức năng cho một người dùng (đã lọc theo vai trò và route tồn tại) */
    public static function groups(User $user): array
    {
        $icons = config('menu.icons', []);
        $badges = [];
        $out = [];

        foreach (config('menu.groups', []) as $g) {
            if (!in_array($user->role, $g['roles'], true)) {
                continue;
            }
            $items = [];
            foreach ($g['items'] as $it) {
                if (isset($it['roles']) && !in_array($user->role, $it['roles'], true)) {
                    continue;
                }
                if (!Route::has($it['route'])) {
                    continue;
                }
                $badge = null;
                if (($it['badge'] ?? null) === 'to_approve') {
                    $badges['to_approve'] ??= Trip::awaiting($user)->count();
                    $badge = $badges['to_approve'] ?: null;
                }
                $items[] = [
                    'label' => $it['label'],
                    'href'  => route($it['route'], $it['params'] ?? []),
                    'icon'  => $icons[$it['icon']] ?? $icons['doc'],
                    'badge' => $badge,
                    'post'  => $it['post'] ?? false,
                ];
            }
            if ($items) {
                $out[] = ['title' => $g['title'], 'color' => $g['color'], 'items' => $items];
            }
        }

        return $out;
    }

    /** Thanh tab dưới cùng (điện thoại) và thanh điều hướng trên (máy tính) */
    public static function tabs(User $user): array
    {
        $i = config('menu.icons');
        $r = fn (string $name, array $params = []) => Route::has($name) ? route($name, $params) : null;
        $filter = request('filter');
        $onTrips = request()->routeIs('trips.*') && !request()->routeIs('trips.create');

        $home = ['label' => 'Trang chủ', 'href' => route('home'), 'icon' => $i['home'], 'active' => request()->routeIs('home')];
        $me = ['label' => 'Cá nhân', 'href' => $r('profile.edit'), 'icon' => $i['user'], 'active' => request()->routeIs('profile.*')];

        if ($user->role === 'student') {
            return array_values(array_filter([
                $home,
                ['label' => 'Hồ sơ', 'href' => $r('student.profile.show'), 'icon' => $i['user'], 'active' => request()->routeIs('student.profile.*')],
                ['label' => 'Hộ chiếu', 'href' => $r('student.passport.index'), 'icon' => $i['doc'], 'active' => request()->routeIs('student.passport.*')],
                $me,
            ], fn ($t) => $t['href']));
        }

        $create = ['label' => 'Tạo mới', 'href' => $r('trips.create'), 'icon' => $i['plus'], 'active' => request()->routeIs('trips.create'), 'primary' => true];
        $files = ['label' => 'Hồ sơ', 'href' => $r('trips.index'), 'icon' => $i['list'], 'active' => $onTrips && !$filter];

        if (in_array($user->role, ['admin', 'bgh', 'htqt'], true)) {
            $fourth = ['label' => 'Tổng quan', 'href' => $r('overview'), 'icon' => $i['grid'], 'active' => request()->routeIs('overview', 'stats.*', 'plans.*')];
        } elseif ($user->role === 'unit_manager') {
            $fourth = ['label' => 'Chờ duyệt', 'href' => $r('trips.index', ['filter' => 'to_approve']), 'icon' => $i['check'], 'active' => $onTrips && $filter === 'to_approve'];
        } else {
            $fourth = ['label' => 'Của tôi', 'href' => $r('trips.index', ['filter' => 'mine']), 'icon' => $i['user'], 'active' => $onTrips && $filter === 'mine'];
        }

        return [$home, $files, $create, $fourth, $me];
    }

    /** Bộ đếm ở thẻ đầu trang chủ */
    public static function counters(User $user): array
    {
        if ($user->role === 'student') {
            return [];
        }
        $visible = fn () => Trip::visibleTo($user);

        $c = [];
        if (in_array($user->role, ['admin', 'bgh', 'htqt', 'unit_manager'], true)) {
            $c[] = ['label' => 'Chờ tôi duyệt', 'value' => Trip::awaiting($user)->count(), 'alert' => true];
        }
        $c[] = ['label' => 'Đang xử lý', 'value' => $visible()->whereIn('status', ['draft', 'returned', 'pending_unit', 'pending_htqt', 'pending_bgh'])->count(), 'alert' => false];
        $c[] = ['label' => 'Cần báo cáo', 'value' => $visible()->where('status', 'approved')->whereDate('return_at', '<', today())->count(), 'alert' => true];

        return $c;
    }
}
