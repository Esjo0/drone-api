<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Drone;
use App\Models\BatteryLog;
use Illuminate\Http\Request;


class DroneCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'drone:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return string
     */
    public function handle()
    {
        $drones = Drone::all();
        foreach ($drones as $drone){
            //log battery capacity
            BatteryLog::create([
                'drone_id' => $drone->id,
                'battery_level' => $drone->battery_capacity
            ]);

            //Set battery State to IDLE if less than 25%
            if($drone->battery_capacity < 25){
                Drone::where('id', $drone->id)
                ->update(['state' => 'IDLE']);
            }
        }

        return 'Log Successful';
    }
}
