<?php

namespace Database\Factories;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Campaign>
 */
class CampaignFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // 'user_id' => User::first()->id,
            'user_id' => 2,
            'title' => fake()->title(),
            'purpose' =>  Arr::random([
                'online_course',
                'training',
                'tution',
            ]),
            'used_in' => Arr::random([
                'courseera',
                'udemy',
                'get_smarter',
            ]),
            'description' => fake()->text(500),
            'feature_image' => Arr::random([
                '01JG6P5YFXEAX1P5398ZPTSY32.png',
                '01JG6P72CC2BFV2P36MNKPGNEG.png',
                '01JG6P81A6P5KE7JC3Z13V6RYX.png'
            ]),
            'gallary_images' => json_encode([
                "01JETDV9YHRG3NFDN84SMA2S7H.jpg",
                "01JETMQ0BJ2BRMP9C6T2RK6WEV.jpg",
                "01JETDV9YEY6FMP3DBE68HH25E.png",
                "01JETMQ0BDF4E305B8X4SGAPMV.png"
            ]),
            'video_url' => fake()->url(),
            'starting_date' => Carbon::now()->subDays(10),
            'ending_date' => Carbon::now()->addDays(30),
            'funding_goal' => fake()->numberBetween(300, 50000),
            'location' => fake()->city(),
            'term_and_conditions' => true,
            'created_at' => Carbon::now()->addDays(4),
        ];
    }
}
