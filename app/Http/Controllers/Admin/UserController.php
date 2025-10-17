<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::withCount('orders');

        // Search functionality
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        // Role filter
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Status filter
        if ($request->filled('status')) {
            if ($request->status === 'verified') {
                $query->whereNotNull('email_verified_at');
            } elseif ($request->status === 'unverified') {
                $query->whereNull('email_verified_at');
            }
        }

        // Sorting
        $sort = $request->get('sort', 'created_at_desc');
        switch ($sort) {
            case 'created_at_asc':
                $query->oldest();
                break;
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            case 'email_asc':
                $query->orderBy('email', 'asc');
                break;
            case 'email_desc':
                $query->orderBy('email', 'desc');
                break;
            default:
                $query->latest();
                break;
        }

        $users = $query->paginate(15)->appends($request->query());

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:customer,admin',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'phone' => $request->phone,
            'address' => $request->address,
            'email_verified_at' => now(), // Auto verify for admin created users
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'Người dùng đã được tạo thành công!');
    }

    public function show(User $user)
    {
        $user->load([
            'orders' => function ($query) {
                $query->latest()->take(10);
            }
        ]);

        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:customer,admin',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'phone' => $request->phone,
            'address' => $request->address,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')
            ->with('success', 'Thông tin người dùng đã được cập nhật!');
    }

    public function destroy(User $user)
    {
        // Prevent deleting the current admin user
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Bạn không thể xóa tài khoản của chính mình!');
        }

        // Check if user has orders
        if ($user->orders()->exists()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Không thể xóa người dùng có đơn hàng. Vui lòng vô hiệu hóa thay vì xóa.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'Người dùng đã được xóa thành công!');
    }

    /**
     * Bulk actions for users
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:verify,unverify,activate,deactivate,delete',
            'ids' => 'required|array',
            'ids.*' => 'exists:users,id'
        ]);

        // Prevent bulk actions on current user
        if (in_array(auth()->id(), $request->ids)) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Bạn không thể thực hiện hành động trên tài khoản của chính mình!');
        }

        $users = User::whereIn('id', $request->ids);

        switch ($request->action) {
            case 'verify':
                $users->update(['email_verified_at' => now()]);
                $message = 'Đã xác thực ' . count($request->ids) . ' người dùng';
                break;
            case 'unverify':
                $users->update(['email_verified_at' => null]);
                $message = 'Đã hủy xác thực ' . count($request->ids) . ' người dùng';
                break;
            case 'delete':
                // Check if any user has orders
                $usersWithOrders = $users->whereHas('orders')->count();
                if ($usersWithOrders > 0) {
                    return redirect()->route('admin.users.index')
                        ->with('error', 'Không thể xóa người dùng có đơn hàng.');
                }
                $users->delete();
                $message = 'Đã xóa ' . count($request->ids) . ' người dùng';
                break;
        }

        return redirect()->route('admin.users.index')
            ->with('success', $message);
    }
}