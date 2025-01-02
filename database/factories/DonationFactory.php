<?php

namespace Database\Factories;

use App\Models\Campaign;
use App\Models\Donation;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Donation>
 */
class DonationFactory extends Factory
{
    protected $model = Donation::class;

    public function definition(): array
    {
        // Fetch all campaign IDs for the given user ID
        $userId = auth()->id();
        $campaignIds = Campaign::where('user_id', 2)->pluck('id')->toArray();
        return [
            // 'campaign_id' => $this->faker->randomElement($campaignIds),
            'campaign_id' => 407,
            'amount' => 200,
            'donor_name' => $this->faker->name,
            'donor_email' => $this->faker->safeEmail,
            'message' => $this->faker->sentence,
            'anonymous' => $this->faker->boolean,
            'tip_percentage' => $this->faker->randomFloat(2, 0, 100),
            'opt_out_tip' => $this->faker->boolean,
            'created_at' => Carbon::now()->addDays(6),
        ];
    }
}
