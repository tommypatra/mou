<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class AkunFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $def = mt_rand(0, 1);
        $kel = array("L", "P");
        $ttl = array("male", "female");

        return [
            'nama' => $this->faker->name($ttl[$def]),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => bcrypt('00000000'), // password
            'kel' => $kel[$def],
            'glrdepan' => $this->faker->title($ttl[$def]),
            'tempatlahir' => $this->faker->city(),
            'tanggallahir' => $this->faker->date('Y-m-d'),
            'alamat' => $this->faker->address(),
            'nohp' => '0852' . $this->faker->randomNumber(8),
        ];
    }
}
