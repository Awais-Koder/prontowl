<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Donation;
use Livewire\WithPagination;

class DonorList extends Component
{
    use WithPagination;
    public $campaignId;
    public $perPage = 10;

    protected $listeners = ['loadMore'];

    public function loadMore()
    {
        $this->perPage += 10; // Load 10 more donors
    }

    public function render()
    {
        $donations = Donation::where('campaign_id', $this->campaignId)
            ->latest()
            ->paginate($this->perPage);

        return view('livewire.donor-list', [
            'donations' => $donations,
        ]);
    }
}
