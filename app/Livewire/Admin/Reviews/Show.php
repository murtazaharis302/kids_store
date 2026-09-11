<?php

namespace App\Livewire\Admin\Reviews;

use App\Models\Review;
use Livewire\Component;

class Show extends Component
{
    public Review $review;
    public $confirmingReviewDeletion = false;

    public function mount(Review $review)
    {
        $this->authorizeAdminOrStaff();
        $this->review = $review->load(['user', 'product.primaryImage', 'order']);
    }

    public function toggleStatus()
    {
        $this->authorizeAdminOrStaff();

        $freshReview = Review::find($this->review->id);
        if ($freshReview) {
            $newStatus = !$freshReview->status;
            $freshReview->update(['status' => $newStatus]);
            $this->review = $freshReview->fresh(['user', 'product.primaryImage', 'order']);
            $statusLabel = $newStatus ? 'Published/Approved' : 'Pending/Unapproved';
            session()->flash('message', "Review status updated to {$statusLabel}.");
        } else {
            session()->flash('error', "Review record not found.");
        }
    }

    public function confirmDelete()
    {
        $this->authorizeAdminOrStaff();
        $this->confirmingReviewDeletion = true;
    }

    public function cancelDelete()
    {
        $this->confirmingReviewDeletion = false;
    }

    public function deleteReview()
    {
        $this->authorizeAdminOrStaff();

        // Fresh database check
        $freshReview = Review::find($this->review->id);
        if ($freshReview) {
            $reviewId = $freshReview->id;
            // Deletes ONLY the review row safely
            $freshReview->delete();
            session()->flash('message', "Review #{$reviewId} permanently deleted.");
            return redirect()->route('admin.reviews.index');
        } else {
            session()->flash('error', "Review record could not be found.");
            $this->cancelDelete();
        }
    }

    protected function authorizeAdminOrStaff()
    {
        if (!auth()->check() || !in_array(auth()->user()->role, ['admin', 'staff'])) {
            abort(403, 'Unauthorized access to admin review module.');
        }
    }

    public function render()
    {
        return view('livewire.admin.reviews.show')
            ->layout('components.layouts.admin', ['title' => 'Review #' . $this->review->id]);
    }
}
