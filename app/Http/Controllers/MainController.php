<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Vars;
use App\Models\Invoice;
use App\Models\Client;
use Spipu\Html2Pdf\Html2Pdf;

class MainController extends Controller
{

    /**
     * @param Request $request
     * @return View $view
     */
    public function mainForm(Request $request){
        $vars = Vars::where('status', true)->get();
        return view('form')->with(['var' => $vars]);
    }

    /**
     * This is the method that processes the Invoice data submit from any device
     * @param Request $request 
     * @return View $view
     */
    public function submitInvoice(Request $request){
        
        $data = json_decode($request->data);

        $info = $this->dismantle($data->info);

        $val = Validator::make($info, [
            "clientname" => 'required|string',
            "direction" => 'required|string',
            "date" => 'required|date',
            "email" => 'required|email',
            "postcode" => 'required|string',
            "phone" => 'sometimes|string',
        ]);

        if($val->fails()){
            throw new \Exception(['error' => $val->errors()]);
        }

        //Generar directamente el archivo pdf, por ahora.

        $file = view('pdf', ['data' => $data, 'info' => $info])->render();

        dd()
        
        $pdf = new Html2Pdf();
        $pdf->writeHTML($file);
        $pdf->output();
    }

    /**
     * Internal method, it translates a serialized string
     * @param string $string
     * @return array $data
     */
    private function dismantle($string){
        $data = [];
        foreach(explode('&', $string) as $pairs){
            $d = explode('=', $pairs);
            $data[$d[0]] = urldecode($d[1]);
        }
        return $data;
    }
}
