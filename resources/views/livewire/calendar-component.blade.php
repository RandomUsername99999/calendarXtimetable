<div>
    @assets
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>
    @endassets

    <div 
        id="calendar-container" 
        wire:ignore 
        x-data 
        x-init="
            const calendar = new FullCalendar.Calendar($refs.calendarCanvas, {
                initialView: 'dayGridMonth',
                editable: true,
                selectable: true,
                
                // Fetch events straight from the Livewire PHP method using Alpine's $wire
                events: function(info, successCallback, failureCallback) {
                    $wire.getEvents().then(events => {
                        successCallback(events);
                    }).catch(error => {
                        console.error('Failed to load events:', error);
                    });
                },

                // When a date is selected, call the PHP method directly
                select: function(info) {
                    const title = prompt('Enter Event Title:');
                    if (title) {
                        $wire.createEvent(title, info.startStr, info.endStr);
                    }
                    calendar.unselect();
                }
            });

            calendar.render();

            // Listen for the Livewire dispatch event to refresh the grid
            Livewire.on('eventAdded', () => {
                calendar.refetchEvents();
            });
        "
        style="max-width: 900px; margin: 0 auto; background: white; padding: 20px;"
    >
        <div x-ref="calendarCanvas"></div>
    </div>
</div>