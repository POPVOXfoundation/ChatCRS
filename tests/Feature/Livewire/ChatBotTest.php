<?php

use App\Livewire\ChatBot;
use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test(ChatBot::class)
        ->assertStatus(200);
});
