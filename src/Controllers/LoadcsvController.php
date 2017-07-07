<?php

namespace Mustaard\Metaphor\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Mustaard\Metaphor;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class LoadcsvController extends Controller
{
    public function convertCsv(Request $request){

        $data  = array(
            'batch_count' => DB::table('batch_uploads')->get()->count(),
            'batch_count_thisweek' => DB::table('leads')->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->get()->count(),
            'lead_count' => DB::table('leads')->get()->count(),
        );

            $filename = $request->file('csv')->getClientOriginalName();
            $path = $request->file('csv')->store('csv');

            $data = \Metaphor::importCsvFile($request->file('csv'), $path, $filename);

            //$batch = DB::table('batch_uploads')->where(['file_name' => $filename])->first();
            $batch = Metaphor\BatchUpload::where(['file_name' => $filename])->first();
            //dd($batch);
            $batch->crm_raw = json_encode($data, true);
            $batch->save();

            $batch_id = $batch->id;
            //$responds = \Mustaard::pushToCrm($data);
            $file = $request->file('csv');
            return view('Metaphor::welcome', compact('data', 'batch_id', 'data'));

    }

    public function upload(){

        $batch = DB::table('batch_uploads')->orderBy('created_at', 'desc')->paginate(7);


        return view('Metaphor::upload', compact('batch'));
    }

    public function pushToCrm(Request $request){

        $batch = Metaphor\BatchUpload::find($request->batch_id);
        $leads = json_decode($batch->crm_raw);

        foreach($leads as $lead){
            $responds[] = \Metaphor::sendLeadDataToCRM($lead);
            sleep(5);
        }

        $total = count($responds);

        return back(compact('total'));
    //dd($responds);

    }
}
