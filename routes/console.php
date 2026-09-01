<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('bookings:expire-pending --minutes=20')->everyMinute();
