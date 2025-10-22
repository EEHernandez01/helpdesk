<?php

namespace App\View\Components;

use Illuminate\View\Component;
use App\Models\Ticket;

class RecentTickets extends Component
{
    public $tickets;

    public function __construct()
    {
        $this->tickets = Ticket::recent()->limit(3)->get();
    }

    public function render()
    {
        return view('components.recent-tickets');
    }
}
