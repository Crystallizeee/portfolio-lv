<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\SiteVisit;
use Illuminate\Support\Facades\DB;

class PurgeSiteVisits extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'analytics:purge-visits 
                            {--days=90 : Number of days to retain raw visit data}
                            {--dry-run : Preview what would be purged without deleting}';

    /**
     * The console command description.
     */
    protected $description = 'Purge site_visits older than the retention period to prevent database bloat';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $retentionDays = (int) $this->option('days');
        $dryRun = $this->option('dry-run');
        $cutoffDate = now()->subDays($retentionDays);

        $this->info("Retention period: {$retentionDays} days");
        $this->info("Cutoff date: {$cutoffDate->toDateTimeString()}");

        // Count records to be purged
        $count = SiteVisit::where('created_at', '<', $cutoffDate)->count();

        if ($count === 0) {
            $this->info('No records to purge.');
            return self::SUCCESS;
        }

        $this->info("Records to purge: {$count}");

        if ($dryRun) {
            $this->warn('[DRY RUN] No records were deleted.');
            return self::SUCCESS;
        }

        // Aggregate daily visit counts before purging
        $this->info('Aggregating visit counts before purge...');
        
        $dailyAggregates = DB::table('site_visits')
            ->where('created_at', '<', $cutoffDate)
            ->select(
                DB::raw('DATE(created_at) as visit_date'),
                DB::raw('COUNT(*) as total_visits'),
                DB::raw('COUNT(DISTINCT visitor_id) as unique_visitors')
            )
            ->groupBy(DB::raw('DATE(created_at)'))
            ->get();

        $this->info("Aggregated {$dailyAggregates->count()} days of data.");

        // Delete in chunks to avoid memory issues
        $deleted = 0;
        SiteVisit::where('created_at', '<', $cutoffDate)
            ->chunkById(1000, function ($visits) use (&$deleted) {
                $ids = $visits->pluck('id')->toArray();
                SiteVisit::whereIn('id', $ids)->delete();
                $deleted += count($ids);
                $this->output->write("\rDeleted: {$deleted}");
            });

        $this->newLine();
        $this->info("Purge complete. {$deleted} records removed.");

        // Log the purge action
        if (\Illuminate\Support\Facades\Auth::check()) {
            \App\Models\ActivityLog::create([
                'user_id' => \Illuminate\Support\Facades\Auth::id(),
                'action' => 'purge',
                'description' => "Purged {$deleted} site_visits older than {$retentionDays} days",
                'model_type' => SiteVisit::class,
                'model_id' => null,
                'properties' => [
                    'retention_days' => $retentionDays,
                    'cutoff_date' => $cutoffDate->toDateTimeString(),
                    'records_deleted' => $deleted,
                    'aggregated_days' => $dailyAggregates->count(),
                ],
            ]);
        }

        return self::SUCCESS;
    }
}
