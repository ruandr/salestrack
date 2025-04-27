<?php

use Illuminate\Support\Facades\Schedule;

// Schedule::command('app:send-daily-sales-summary')->dailyAt('23:59');
Schedule::command('app:send-daily-sales-summary')->everyMinute();

