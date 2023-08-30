<?php

namespace App\Console\Commands;

use App\Http\Controllers\DocumentController;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class RemoveDocExpire extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cron:removedocexpire';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check Document expire and remove document daily';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // $query = "SELECT * FROM (
        //     SELECT
        //     documents.id, ref_id, ref_doc_id doc_id, ref_user_id, ref_dep_id, image_name, file_name,
        //     DATE_FORMAT(documents.created_at,'%d/%m/%Y') create_doc_date,
        //     DATE_FORMAT(expire_date_at,'%d/%m/%Y') expire_doc_date,
        //     DATEDIFF(expire_date_at, NOW()) remain_date
        //     FROM `documents`
        // )t
        // WHERE remain_date < 0";

        // $documents = DB::select(DB::raw($query));

        // Resolve an instance of YourController
        $controller = app(DocumentController::class);

        // Call the method on the controller
        $result = $controller->getAllExpireForDel();

        foreach ($result as $key => $value) {
            // \Log::info("Deleted id->" . $value->id);
            \Log::info($controller->deleteFlag($value->id));
        }
    }
}
