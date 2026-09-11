<?php

namespace App\Livewire\Admin\Reviews;

use App\Models\Review;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $ratingFilter = '';
    public $statusFilter = '';
    public $dateFilter = '';

    public $confirmingReviewDeletion = false;
    public $reviewToDeleteId = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'ratingFilter' => ['except' => ''],
        'statusFilter' => ['except' => ''],
        'dateFilter' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingRatingFilter()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function updatingDateFilter()
    {
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->reset(['search', 'ratingFilter', 'statusFilter', 'dateFilter']);
        $this->resetPage();
    }

    public function toggleStatus($id)
    {
        $this->authorizeAdminOrStaff();

        $review = Review::find($id);
        if ($review) {
            $newStatus = !$review->status;
            $review->update(['status' => $newStatus]);
            $statusLabel = $newStatus ? 'Published/Approved' : 'Pending/Unapproved';
            session()->flash('message', "Review #{$review->id} status updated to {$statusLabel}.");
        } else {
            session()->flash('error', "Review record not found.");
        }
    }

    public function confirmDelete($id)
    {
        $this->authorizeAdminOrStaff();

        $review = Review::find($id);
        if ($review) {
            $this->reviewToDeleteId = $review->id;
            $this->confirmingReviewDeletion = true;
        }
    }

    public function cancelDelete()
    {
        $this->confirmingReviewDeletion = false;
        $this->reviewToDeleteId = null;
    }

    public function deleteReview()
    {
        $this->authorizeAdminOrStaff();

        if (!$this->reviewToDeleteId) {
            return;
        }

        // Authoritative Fresh Database Check immediately prior to deletion
        $review = Review::find($this->reviewToDeleteId);
        if ($review) {
            $reviewId = $review->id;
            // Deletes ONLY the review row safely
            $review->delete();
            session()->flash('message', "Review #{$reviewId} permanently deleted.");
        } else {
            session()->flash('error', "Review record could not be found.");
        }

        $this->cancelDelete();
    }

    protected function authorizeAdminOrStaff()
    {
        if (!auth()->check() || !in_array(auth()->user()->role, ['admin', 'staff'])) {
            abort(403, 'Unauthorized access to review management.');
        }
    }

    public function render()
    {
        $query = Review::with(['user', 'product.primaryImage', 'order']);

        if (!empty($this->search)) {
            $searchTerm = '%' . trim($this->search) . '%';
            $query->where(function ($q) use ($searchTerm) {
                $q->where('comment', 'like', $searchTerm)
                  ->orWhereHas('user', function ($uq) use ($searchTerm) {
                      $uq->where('name', 'like', $searchTerm)
                        ->orWhere('email', 'like', $searchTerm);
                  })
                  ->orWhereHas('product', function ($pq) use ($searchTerm) {
                      $pq->where('name', 'like', $searchTerm);
                  });
            });
        }

        if ($this->ratingFilter !== '') {
            $query->where('rating', (int) $this->ratingFilter);
        }

        if ($this->statusFilter !== '') {
            if ($this->statusFilter === 'published' || $this->statusFilter === '1') {
                $query->where('status', true);
            } elseif ($this->statusFilter === 'pending' || $this->statusFilter === '0') {
                $query->where('status', false);
            }
        }

        if ($this->dateFilter !== '') {
            if ($this->dateFilter === 'today') {
                $query->whereDate('created_at', now()->today());
            } elseif ($this->dateFilter === 'this_week') {
                $query->where('created_at', '>=', now()->startOfWeek());
            } elseif ($this->dateFilter === 'this_month') {
                $query->where('created_at', '>=', now()->startOfMonth());
            }
        }

        $reviews = $query->latest()->paginate(15);

        // Calculate Summary Metrics
        $totalReviews = Review::count();
        $publishedReviews = Review::where('status', true)->count();
        $pendingReviews = Review::where('status', false)->count();
        $averageRating = $totalReviews > 0 ? round((float) Review::avg('rating'), 1) : 0;

        $starBreakdown = [
            5 => Review::where('rating', 5)->count(),
            4 => Review::where('rating', 4)->count(),
            3 => Review::where('rating', 3)->count(),
            2 => Review::where('rating', 2)->count(),
            1 => Review::where('rating', 1)->count(),
        ];

        return view('livewire.admin.reviews.index', [
            'reviews' => $reviews,
            'totalReviews' => $totalReviews,
            'publishedReviews' => $publishedReviews,
            'pendingReviews' => $pendingReviews,
            'averageRating' => $averageRating,
            'starBreakdown' => $starBreakdown,
        ])->layout('components.layouts.admin', ['title' => 'Reviews Management']);
    }
}
