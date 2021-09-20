<?php

namespace Database\Factories;

use App\Model;
use App\Models\Paste;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PasteFactory extends Factory
{
    protected $model = Paste::class;

    public function definition(): array
    {
    	return [
            'user_id' => User::factory(),
            'slug' => Str::random(8),
            'Paste' => collect($this->faker->paragraphs(6))->map(fn($item) => "\n{$item}\n")->implode(''),
            'isPrivate' => true
    	];
    }
}
