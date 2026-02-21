@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid">
    <div class="page-header">
        <h2><i class="fas fa-tachometer-alt"></i> Dashboard</h2>
    </div>

    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-primary bg-gradient text-white rounded p-3">
                                <i class="fas fa-file-alt fa-2x"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="mb-0" id="totalPosts">0</h5>
                            <p class="text-muted mb-0">Total Posts</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-warning bg-gradient text-white rounded p-3">
                                <i class="fas fa-clock fa-2x"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="mb-0" id="pendingPosts">0</h5>
                            <p class="text-muted mb-0">Pending Review</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-success bg-gradient text-white rounded p-3">
                                <i class="fas fa-check-circle fa-2x"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="mb-0" id="approvedPosts">0</h5>
                            <p class="text-muted mb-0">Approved</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span><i class="fas fa-list"></i> Recent Posts</span>
            <button class="btn btn-primary btn-sm" onclick="showCreateModal()">
                <i class="fas fa-plus"></i> New Post
            </button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" id="postsTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Title</th>
                            <th>Author</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="postsTableBody">
                        <tr>
                            <td colspan="6" class="text-center">Loading...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="postModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="postModalTitle">Create New Post</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="postForm">
                    <input type="hidden" id="postId">
                    <div class="mb-3">
                        <label class="form-label">Title</label>
                        <input type="text" class="form-control" id="postTitle" placeholder="Enter post title">
                        <div class="error-text" id="titleError"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Content</label>
                        <textarea class="form-control" id="postBody" rows="6" placeholder="Write your post content here..."></textarea>
                        <div class="error-text" id="bodyError"></div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="savePostBtn" onclick="savePost()">Save Post</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="rejectModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Reject Post</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="rejectForm">
                    <input type="hidden" id="rejectPostId">
                    <div class="mb-3">
                        <label class="form-label">Rejection Reason</label>
                        <textarea class="form-control" id="rejectReason" rows="4" placeholder="Enter reason for rejection (minimum 10 characters)"></textarea>
                        <div class="error-text" id="rejectReasonError"></div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" onclick="submitReject()">Reject Post</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="logsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Activity Logs</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-sm" id="logsTable">
                        <thead>
                            <tr>
                                <th>Action</th>
                                <th>User</th>
                                <th>Post</th>
                                <th>Details</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody id="logsTableBody">
                            <tr>
                                <td colspan="5" class="text-center">Loading...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
const user = getUser();
const isManager = user && (user.role === 'manager' || user.role === 'admin');

$(document).ready(function() {
    if (!isAuthenticated()) {
        window.location.href = '/login';
        return;
    }
    loadPosts();
});

function loadPosts() {
    $.ajax({
        url: '/api/posts',
        method: 'GET',
        success: function(response) {
            const posts = response.data;
            updateStats(posts);
            renderPosts(posts);
        },
        error: function(xhr) {
            if (xhr.status === 401) {
                removeToken();
                removeUser();
                window.location.href = '/login';
            }
        }
    });
}

function updateStats(posts) {
    $('#totalPosts').text(posts.length);
    $('#pendingPosts').text(posts.filter(p => p.status === 'pending').length);
    $('#approvedPosts').text(posts.filter(p => p.status === 'approved').length);
}

function renderPosts(posts) {
    const tbody = $('#postsTableBody');
    tbody.empty();
    
    if (posts.length === 0) {
        tbody.html('<tr><td colspan="6" class="text-center">No posts found</td></tr>');
        return;
    }

    posts.forEach(post => {
        const statusBadge = getStatusBadge(post.status);
        const row = `
            <tr>
                <td>${post.id}</td>
                <td><strong>${post.title}</strong></td>
                <td>${post.author ? post.author.name : 'Unknown'}</td>
                <td>${statusBadge}</td>
                <td>${formatDate(post.created_at)}</td>
                <td class="action-btns">
                    ${getActionButtons(post)}
                </td>
            </tr>
        `;
        tbody.append(row);
    });
}

function getStatusBadge(status) {
    const badges = {
        'pending': '<span class="status-badge status-pending"><i class="fas fa-clock"></i> Pending</span>',
        'approved': '<span class="status-badge status-approved"><i class="fas fa-check"></i> Approved</span>',
        'rejected': '<span class="status-badge status-rejected"><i class="fas fa-times"></i> Rejected</span>'
    };
    return badges[status] || status;
}

function getActionButtons(post) {
    let buttons = '';
    
    if (post.status === 'pending' && isManager) {
        buttons += `<button class="btn btn-success btn-sm me-1" onclick="approvePost(${post.id})" title="Approve"><i class="fas fa-check"></i></button>`;
        buttons += `<button class="btn btn-danger btn-sm me-1" onclick="showRejectModal(${post.id})" title="Reject"><i class="fas fa-times"></i></button>`;
    }
    
    buttons += `<button class="btn btn-info btn-sm me-1" onclick="viewLogs(${post.id})" title="View Logs"><i class="fas fa-history"></i></button>`;
    
    if (user.role === 'admin') {
        buttons += `<button class="btn btn-danger btn-sm" onclick="deletePost(${post.id})" title="Delete"><i class="fas fa-trash"></i></button>`;
    }
    
    return buttons;
}

function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' });
}

function showCreateModal() {
    $('#postModalTitle').text('Create New Post');
    $('#postId').val('');
    $('#postTitle').val('');
    $('#postBody').val('');
    $('.error-text').text('');
    $('#postModal').modal('show');
}

function savePost() {
    const postId = $('#postId').val();
    const title = $('#postTitle').val();
    const body = $('#postBody').val();
    
    $('.error-text').text('');
    $('.form-control').removeClass('is-invalid');
    
    let hasError = false;
    if (!title) {
        $('#titleError').text('Title is required');
        $('#postTitle').addClass('is-invalid');
        hasError = true;
    }
    if (!body) {
        $('#bodyError').text('Content is required');
        $('#postBody').addClass('is-invalid');
        hasError = true;
    }
    
    if (hasError) return;
    
    $('#savePostBtn').prop('disabled', true).text('Saving...');
    
    const url = postId ? `/api/posts/${postId}` : '/api/posts';
    const method = postId ? 'PUT' : 'POST';
    
    $.ajax({
        url: url,
        method: method,
        contentType: 'application/json',
        data: JSON.stringify({ title, body }),
        success: function(response) {
            $('#postModal').modal('hide');
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: response.message,
                timer: 2000,
                showConfirmButton: false
            });
            loadPosts();
        },
        error: function(xhr) {
            if (xhr.status === 422) {
                const errors = xhr.responseJSON.errors;
                if (errors.title) {
                    $('#titleError').text(errors.title[0]);
                    $('#postTitle').addClass('is-invalid');
                }
                if (errors.body) {
                    $('#bodyError').text(errors.body[0]);
                    $('#postBody').addClass('is-invalid');
                }
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Something went wrong'
                });
            }
        },
        complete: function() {
            $('#savePostBtn').prop('disabled', false).text('Save Post');
        }
    });
}

function approvePost(postId) {
    Swal.fire({
        title: 'Approve Post',
        text: 'Are you sure you want to approve this post?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, Approve it!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: `/api/posts/${postId}/approve`,
                method: 'POST',
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Approved',
                        text: response.message,
                        timer: 2000,
                        showConfirmButton: false
                    });
                    loadPosts();
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: xhr.responseJSON?.error || 'Something went wrong'
                    });
                }
            });
        }
    });
}

function showRejectModal(postId) {
    $('#rejectPostId').val(postId);
    $('#rejectReason').val('');
    $('#rejectReasonError').text('');
    $('#rejectModal').modal('show');
}

function submitReject() {
    const postId = $('#rejectPostId').val();
    const reason = $('#rejectReason').val();
    
    $('#rejectReasonError').text('');
    $('#rejectReason').removeClass('is-invalid');
    
    if (!reason || reason.length < 10) {
        $('#rejectReasonError').text('Reason must be at least 10 characters');
        $('#rejectReason').addClass('is-invalid');
        return;
    }
    
    $.ajax({
        url: `/api/posts/${postId}/reject`,
        method: 'POST',
        contentType: 'application/json',
        data: JSON.stringify({ reason }),
        success: function(response) {
            $('#rejectModal').modal('hide');
            Swal.fire({
                icon: 'success',
                title: 'Rejected',
                text: response.message,
                timer: 2000,
                showConfirmButton: false
            });
            loadPosts();
        },
        error: function(xhr) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: xhr.responseJSON?.error || 'Something went wrong'
            });
        }
    });
}

function viewLogs(postId) {
    $.ajax({
        url: `/api/posts/${postId}/logs`,
        method: 'GET',
        success: function(response) {
            renderLogs(response.data);
            $('#logsModal').modal('show');
        },
        error: function(xhr) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Failed to load logs'
            });
        }
    });
}

function renderLogs(logs) {
    const tbody = $('#logsTableBody');
    tbody.empty();
    
    if (logs.length === 0) {
        tbody.html('<tr><td colspan="5" class="text-center">No logs found</td></tr>');
        return;
    }
    
    logs.forEach(log => {
        const actionBadge = getActionBadge(log.action);
        const meta = log.meta ? JSON.stringify(log.meta) : '-';
        const row = `
            <tr>
                <td>${actionBadge}</td>
                <td>${log.user ? log.user.name : 'Unknown'}</td>
                <td><small>${log.post?.title || '-'}</small></td>
                <td><small>${meta}</small></td>
                <td>${formatDate(log.created_at)}</td>
            </tr>
        `;
        tbody.append(row);
    });
}

function getActionBadge(action) {
    const badges = {
        'created': '<span class="badge bg-primary">Created</span>',
        'approved': '<span class="badge bg-success">Approved</span>',
        'rejected': '<span class="badge bg-danger">Rejected</span>',
        'deleted': '<span class="badge bg-dark">Deleted</span>'
    };
    return badges[action] || action;
}

function deletePost(postId) {
    Swal.fire({
        title: 'Delete Post',
        text: 'Are you sure you want to delete this post? This action cannot be undone.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, Delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: `/api/posts/${postId}`,
                method: 'DELETE',
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Deleted',
                        text: response.message,
                        timer: 2000,
                        showConfirmButton: false
                    });
                    loadPosts();
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: xhr.responseJSON?.error || 'Something went wrong'
                    });
                }
            });
        }
    });
}

function showAllLogs() {
    $.ajax({
        url: '/api/posts',
        method: 'GET',
        success: function(response) {
            const posts = response.data;
            let allLogs = [];
            const requests = posts.map(post => {
                return $.ajax({
                    url: `/api/posts/${post.id}/logs`,
                    method: 'GET'
                }).then(logResponse => {
                    logResponse.data.forEach(log => {
                        log.post_title = post.title;
                    });
                    allLogs = allLogs.concat(logResponse.data);
                });
            });
            
            $.when(...requests).done(() => {
                allLogs.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
                renderAllLogs(allLogs);
                $('#logsModal').modal('show');
            });
        },
        error: function(xhr) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Failed to load activity logs'
            });
        }
    });
}

function renderAllLogs(logs) {
    const tbody = $('#logsTableBody');
    tbody.empty();
    
    if (logs.length === 0) {
        tbody.html('<tr><td colspan="5" class="text-center">No activity logs found</td></tr>');
        return;
    }
    
    logs.forEach(log => {
        const actionBadge = getActionBadge(log.action);
        const meta = log.meta ? JSON.stringify(log.meta) : '-';
        const row = `
            <tr>
                <td>${actionBadge}</td>
                <td>${log.user ? log.user.name : 'Unknown'}</td>
                <td><small>${log.post_title || '-'}</small></td>
                <td><small>${meta}</small></td>
                <td>${formatDate(log.created_at)}</td>
            </tr>
        `;
        tbody.append(row);
    });
}
</script>
@endsection
