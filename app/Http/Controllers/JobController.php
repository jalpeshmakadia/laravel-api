<?php

namespace App\Http\Controllers;

use App\Models\Urls;
use Illuminate\Http\Request;
use App\Jobs\ScrapeDataJob;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\DB;


class JobController extends Controller
{

    /**
     * Create a job
    */
    public function createJob(Request $request)
    {
        $response['status'] = null;

        try {

            // Validate the incoming request
            $data = $request->validate([
                'urls' => 'required|array',
                'urls.*' => 'required|url'
            ]);

            // Extract the URLs from the request
            $urls = $data['urls'];

            // Process save each URL in database
            foreach ($urls as $url) {

                $urlsModal = new Urls();
                $urlsModal->url = $url;
                $urlsModal->save();

                //Dispatch the queue for each url
                ScrapeDataJob::dispatch($urlsModal);
            }

            $response['status'] = 200;
            $response['message'] = 'Urls has been saved.';

        } catch (\Illuminate\Validation\ValidationException $th) {

            $response['status'] = 500;
            $response['message'] = $th->validator->errors();;

        } catch (\Exception $e) {

            $response['status'] = $e->getCode();
            $response['message'] = $e->getMessage();

        }

        return $response;
    }

    /**
     * Retrieve a job by id.
     */
    public function getJob($id)
    {
        $response['status'] = null;

        $urlData = Redis::get('job:'.$id);

        if (!empty($urlData)) {
            $response['status'] = 200;
            $response['data'] = json_decode($urlData, true);
        } else {
            $response['status'] = 404;
            $response['message'] = 'Job data does not exist.';
        }
        return $response;
    }

    /**
     * Remove a job by id.
     */
    public function deleteJob($id)
    {
        try {
            // Delete data from database and redis

            DB::table('urls')->where('id', '=', $id)->delete();
            Redis::del('job:'.$id);

            $response['status'] = 200;
            $response['message'] = 'Url has been deleted successfully.';

        } catch (\Exception $e) {

            $response['status'] = $e->getCode();
            $response['message'] = $e->getMessage();

        }

        return $response;
    }
}
