<?php

namespace Tests\Unit;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class SchedulerTest extends TestCase
{
    public function test_metal_sync_is_scheduled_twice_daily_with_constraints()
    {
        $schedule = $this->app->make(Schedule::class);

        // Require the console routes to register schedule entries
        require base_path('routes/console.php');

        $events = $schedule->events();

        $found = null;
        foreach ($events as $event) {
            if (str_contains($event->command, 'metal:sync-prices')) {
                $found = $event;
                break;
            }
        }

        $this->assertNotNull($found, 'Scheduled event for metal:sync-prices not found');

        // Twice daily at 9 and 15 should be expression '0 9,15 * * *'
        $this->assertEquals('0 9,15 * * *', $found->expression);
        $this->assertEquals('America/New_York', $found->timezone);
        $this->assertTrue($found->withoutOverlapping);
        $this->assertTrue($found->runInBackground);
        $this->assertTrue(property_exists($found, 'onOneServer') ? $found->onOneServer : false, 'Scheduled event should be configured to run on one server');
    }
}
