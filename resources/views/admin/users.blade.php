@extends('layouts.admin')

@section('title', 'Kelola Users')
@section('panel_title', 'Admin Panel')
@section('role_name', 'Administrator')
@section('role_badge', 'Admin')

@section('sidebar')
    <a href="{{ route('admin.dashboard') }}" class="nav-link"><i class="fas fa-chart-line"></i> Dashboard</a>
    <a href="{{ route('admin.users') }}" class="nav-link active"><i class="fas fa-users"></i> Kelola Users</a>
    <a href="{{ route('admin.flights') }}" class="nav-link"><i class="fas fa-plane"></i> Penerbangan</a>
    <a href="{{ route('admin.bookings') }}" class="nav-link"><i class="fas fa-book"></i> Kelola Booking</a>
    <a href="{{ route('admin.airlines') }}" class="nav-link"><i class="fas fa-building"></i> Maskapai</a>
    <a href="{{ route('admin.airports') }}" class="nav-link"><i class="fas fa-map-marker-alt"></i> Bandara</a>
    <a href="{{ route('admin.airplanes') }}" class="nav-link"><i class="fas fa-subway"></i> Pesawat</a>
@endsection

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h2><i class="fas fa-users me-2"></i>Kelola Users</h2>
        <p>Manajemen seluruh pengguna sistem</p>
    </div>
    <a href="{{ route('admin.users.create') }}" class="btn btn-gold-custom">
        <i class="fas fa-plus me-1"></i>Tambah User
    </a>
</div>

<div class="card content-card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-custom mb-0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Phone</th>
                        <th>Tgl Daftar</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $index => $user)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td><strong>{{ $user->name }}</strong></td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @php
                                $badgeColor = match($user->role) {
                                    'admin' => 'danger',
                                    'manager' => 'success',
                                    'staff' => 'info',
                                    default => 'primary'
                                };
                            @endphp
                            <span class="badge bg-{{ $badgeColor }} badge-role">{{ ucfirst($user->role) }}</span>
                        </td>
                        <td>{{ $user->phone ?? '-' }}</td>
                        <td>{{ $user->created_at->format('d M Y') }}</td>
                        <td>
                            <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-primary-custom">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button type="button" class="btn btn-sm btn-danger delete-user-btn" data-user-id="{{ $user->id }}" data-user-name="{{ $user->name }}">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">Belum ada data user.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-custom">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-exclamation-triangle me-2"></i>Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Yakin ingin menghapus user <strong id="deleteUserName"></strong>?</p>
                <p class="text-muted small">User yang dihapus tidak akan bisa login kembali kecuali melakukan registrasi ulang.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">
                    <i class="fas fa-trash me-1"></i>Hapus
                </button>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
// Store the delete URL globally
const DELETE_USER_URL = '{{ route("admin.users.delete", ":id") }}'.replace(':id', '');

document.addEventListener('DOMContentLoaded', function() {
    let deleteUserId = null;
    let deleteRow = null;
    let csrfToken = document.querySelector('meta[name="csrf-token"]');
    
    console.log('Admin users page loaded, CSRF token:', csrfToken ? 'Found' : 'Not found');
    
    // Initialize modal
    const deleteModalElement = document.getElementById('deleteModal');
    const deleteModal = new bootstrap.Modal(deleteModalElement);

    // Handle delete button click
    document.querySelectorAll('.delete-user-btn').forEach(button => {
        button.addEventListener('click', function() {
            deleteUserId = this.dataset.userId;
            deleteRow = this.closest('tr');
            document.getElementById('deleteUserName').textContent = this.dataset.userName;
            console.log('Delete button clicked for user ID:', deleteUserId);
            deleteModal.show();
        });
    });

    // Handle confirm delete
    document.getElementById('confirmDeleteBtn').addEventListener('click', async function() {
        if (!deleteUserId) {
            console.log('No deleteUserId set');
            return;
        }

        const button = this;
        const originalText = button.innerHTML;
        const deleteUrl = `/admin/users/${deleteUserId}`;
        
        console.log('Attempting to delete user at:', deleteUrl);
        console.log('CSRF Token:', csrfToken ? csrfToken.getAttribute('content') : 'NOT FOUND');
        
        try {
            button.disabled = true;
            button.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Menghapus...';

            console.log('Making DELETE request to:', deleteUrl);
            console.log('With CSRF token:', csrfToken ? 'YES' : 'NO');
            
            const response = await fetch(deleteUrl, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken ? csrfToken.getAttribute('content') : 'MISSING',
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                },
                credentials: 'same-origin',
            });

            console.log('✓ Response received');
            console.log('  Status:', response.status);
            console.log('  Status Text:', response.statusText);
            console.log('  Content-Type:', response.headers.get('content-type'));
            console.log('  URL:', response.url);
            
            const contentType = response.headers.get('content-type');
            let data;
            
            try {
                data = await response.json();
                console.log('✓ JSON parsed successfully:', data);
            } catch (jsonError) {
                console.error('✗ Failed to parse JSON:', jsonError);
                const text = await response.text();
                console.log('  Response text:', text.substring(0, 500));
                throw new Error('Server tidak mengembalikan JSON. Status: ' + response.status + ' - ' + response.statusText);
            }

            if (data.success) {
                console.log('Delete successful, removing row...');
                // Remove row with animation
                deleteRow.style.transition = 'all 0.5s ease';
                deleteRow.style.opacity = '0';
                deleteRow.style.transform = 'translateX(-100%)';
                
                setTimeout(() => {
                    console.log('Row removed from DOM');
                    deleteRow.remove();
                }, 500);

                // Show success message
                showToast('User berhasil dihapus!', 'success');
            } else {
                console.log('Delete failed:', data.message);
                showToast(data.message || 'Gagal menghapus user', 'error');
                button.disabled = false;
                button.innerHTML = originalText;
            }
        } catch (error) {
            console.error('Error deleting user:', error);
            showToast('Terjadi kesalahan: ' + error.message, 'error');
            button.disabled = false;
            button.innerHTML = originalText;
        } finally {
            deleteModal.hide();
            deleteUserId = null;
            deleteRow = null;
        }
    });

    // Toast notification function
    function showToast(message, type = 'success') {
        // Remove existing toasts
        document.querySelectorAll('.toast-container').forEach(el => el.remove());
        
        const toastContainer = document.createElement('div');
        toastContainer.className = 'toast-container';
        toastContainer.style.cssText = 'position: fixed; top: 80px; right: 20px; z-index: 9999;';
        document.body.appendChild(toastContainer);

        const toast = document.createElement('div');
        toast.className = `alert alert-${type === 'success' ? 'success' : 'danger'} alert-dismissible fade show`;
        toast.style.cssText = 'min-width: 300px; box-shadow: 0 4px 12px rgba(0,0,0,0.15);';
        toast.innerHTML = `
            <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'} me-2"></i>
            <strong>${message}</strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        toastContainer.appendChild(toast);

        setTimeout(() => {
            toast.style.transition = 'opacity 0.3s ease';
            toast.style.opacity = '0';
            setTimeout(() => {
                if (toastContainer.parentNode) {
                    toastContainer.remove();
                }
            }, 300);
        }, 3000);
    }

    // Reset modal when hidden
    deleteModalElement.addEventListener('hidden.bs.modal', function () {
        const button = document.getElementById('confirmDeleteBtn');
        button.disabled = false;
        button.innerHTML = '<i class="fas fa-trash me-1"></i>Hapus';
        deleteUserId = null;
        deleteRow = null;
    });
});
</script>
@endsection
