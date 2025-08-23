<?php

namespace Database\Factories;

use App\Models\Booking;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

class BookingFactory extends Factory
{
    protected $model = Booking::class;

    public function definition(): array
    {
        return [
            'booking_code' => 'BK' . $this->faker->unique()->numberBetween(100000, 999999),
            'user_id' => User::factory(),
            'option_id' => 1, // Default option
            'check_in_date' => Carbon::now()->addDays(1),
            'check_out_date' => Carbon::now()->addDays(3),
            'total_price_vnd' => $this->faker->numberBetween(1000000, 5000000),
            'guest_count' => $this->faker->numberBetween(1, 4),
            'adults' => $this->faker->numberBetween(1, 2),
            'children' => $this->faker->numberBetween(0, 2),
            'children_age' => null,
            'status' => 'pending',
            'guest_name' => $this->faker->name(),
            'guest_email' => $this->faker->email(),
            'guest_phone' => $this->faker->phoneNumber(),
            'quantity' => 1,
            'room_id' => null,
            'room_type_id' => 1,
            'notes' => null,
        ];
    }

    public function confirmed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'confirmed',
        ]);
    }

    public function checkedIn(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'checked_in',
        ]);
    }

    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'completed',
        ]);
    }

    public function checkedOut(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'checked_out',
        ]);
    }
}
