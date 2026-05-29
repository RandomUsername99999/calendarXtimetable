<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Event;

class CalendarComponent extends Component
{
    // Livewire listener to catch events dispatched from JavaScript
    protected $listeners = ['saveEvent'];

    public function render()
    {
        return view('livewire.calendar-component');
    }

    // This fetches events for FullCalendar
    public function getEvents()
    {
        // Make sure 'return' is present inside the map function!
        return Event::all()->map(function ($event) {
            return [
                'id'    => $event->id,
                'title' => $event->title,
                'start' => $event->start,
                'end'   => $event->end,
            ];
        })->toArray();
    }

    // This creates the event directly via PHP when called by JS
    public function createEvent($title, $start, $end)
    {
        Event::create([
            'title' => $title,
            'start' => $start,
            'end' => $end,
        ]);

        // Refresh the page or emit an event to update the UI
        $this->dispatch('eventAdded');
    }
}
