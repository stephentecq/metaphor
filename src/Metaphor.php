<?php
/**
 * Created by PhpStorm.
 * User: stephenmunabo
 * Date: 5/18/17
 * Time: 9:19 AM
 */




namespace Mustaard\Metaphor;

// Constants
define("LEAD_NOTIFICATION_EMAIL_ADDR", 'smunabo@nobilishealth.com');
define("ERROR_NOTIFICATION_EMAIL_ADDR", 'smunabo@nobilishealth.com');
define("CRM_SERVER_ADDR", 'https://crm.northamericanspine.com/api/marketing/patient-data');  // New CRM Marketing API
define("CRM_SERVER_API_KEY_NAS", 'SU7ZNLJjXHRT0dhKYTnQjGn2');
define("CRM_SERVER_API_KEY_MTCA", 'OQu5G9TRX2VV6LCeu1EU0WWg');


class Metaphor implements MetaphorMap
{

    public function createBatch($data){

    }

    public function importCsv($csvFile = __DIR__.'/NAS_2017Form2.1_Leads_2017-05-11_2017-05-13.csv')
    {
        return array_map('str_getcsv', file($csvFile));
    }

    public function makeArrayFromRow($row){
        $has_   = false;
        $hasCol = false;
        $value  = array();

        //checking to see if there are multiple key values
        (strpos($row, '_') !== false ? $has_ = true : '');

        (strpos($row, ':') !== false ? $hasCol = true : '');


        if($has_ == false && $hasCol == false){
            $value = $row;
        }
        elseif($has_ == false && $hasCol == true)
        {
            //var_dump($this->is_unix_timestamp('2017-05-14T16:45:58-04:00')); exit;
            if($row){
                if($this->is_unix_timestamp($row) == true){
                    $value = $row;
                }else{
                    list ($key,$value) = explode (':',$row);
                    $value  =  trim(preg_replace('/\s+/', ' ', $value));
                }
            }
        }
        elseif($has_ == true && $hasCol == true)
        {

            $temp = explode ('_',$row);
            foreach ($temp as $pair) {
                //var_dump($pair); exit;
                list ($k,$v) = explode (':',$pair);
                $value[$k] = trim(preg_replace('/\s+/', ' ', $v));
            }

        }
        elseif($has_ == true && $hasCol == false){
             $value = $row;
        }
        return $value;
    }

    public function importCsvFile($csvFile = __DIR__.'/NAS_2017Form2.1_Leads_2017-05-11_2017-05-13.csv', $path, $filename){

        $rows = file($csvFile);
        $header = array_shift($rows); //get the header out
        $header = explode(",", $header);
        $final_array = array();
        $x = 0;
        foreach ($rows as $row) {
            $row = explode(",", $row);

            $final_array[] = array(
                $header[0]      => $row[0],
                $header[1]      => $this->makeArrayFromRow($row[1]),
                $header[2]      => $this->makeArrayFromRow($row[2]),
                $header[3]      => $this->makeArrayFromRow($row[3]),
                $header[4]      => $this->makeArrayFromRow($row[4]),
                $header[5]      => $this->makeArrayFromRow($row[5]),
                $header[6]      => $this->makeArrayFromRow($row[6]),
                $header[7]      => $this->makeArrayFromRow($row[7]),
                $header[8]      => $this->makeArrayFromRow($row[8]),
                $header[9]      => $this->makeArrayFromRow($row[9]),
                $header[10]     => $this->makeArrayFromRow($row[10]),
                $header[11]     => $this->makeArrayFromRow($row[11]),
                $header[12]     => $this->makeArrayFromRow($row[12]),
                $header[13]     => $this->makeArrayFromRow($row[13]),
                $header[14]     => $this->makeArrayFromRow($row[14]),
                $header[15]     => $this->makeArrayFromRow($row[15]),
                $header[16]     => $this->makeArrayFromRow($row[16]),
                trim(preg_replace('/\s+/', ' ', $header[17]))     => $this->makeArrayFromRow($row[17]),

            );}

    if($path !== NULL){
        $batch = new BatchUpload();
        $batch->original_csv = $path;
        $batch->file_name = $filename;
        $batch->raw = json_encode($final_array, true);
        $batch->save();
    }

        $mapped = $this->mapCsvToCrmFields($final_array);

        return $mapped;

    }


    public function is_unix_timestamp($strTimestamp){

        if($strTimestamp[10] == 'T') {
            return true;
        }
    }



    public function getProperCrmKey($formName){

        //var_dump(strpos($formName, 'NAS')); exit;
        if(strpos($formName, 'NAS') !== false){
            $apiKey = 'SU7ZNLJjXHRT0dhKYTnQjGn2';
        }else {
            $apiKey = 'OQu5G9TRX2VV6LCeu1EU0WWg';
        }
        return $apiKey;
    }


    public function mapCsvToCrmFields($data){

        foreach($data as $row){

            //echo '<pre>';
            //var_dump($this->matchBrandToVerticalByFormId($row['form_id'])['vertical']); exit;
            //echo '</pre>';
            //var_dump($row['campaign_name']['brand']); exit;
            //echo '</pre>';
            $params[] = array(
                'api-key' => $this->getProperCrmKey($row['form_name']),
                'patient' => array(
                    'first_name'    => $row['first_name'],
                    'last_name'     => $row['last_name'],
                    'home_phone'    => $row['phone_number'],
                    'email_1'       => $row['email'],
                    'insurer_id'    => $this->transformInsurance($row['who_is_your_insurance_carrier?']),
                    'vertical_id'   => $this->matchBrandToVerticalByFormId($row['form_id'])['vertical'],
                ),
                'source' => array(
                    'name' => 'facebook',
                    'form-name' => $row['form_name'],
                    'details' => array(
                        'fb_created_time'  => ($row['created_time'] ? $row['created_time'] : NULL),
                        'fb_id'            => $row['id'],
                        'fb_adset_id'      => $row['adset_id'],
                        'fb_campaign_id'   => $row['campaign_id'],
                        'fb_form_id'       => $row['form_id'],
                        'fb_is_organic'    => $row['is_organic'],
                        'fb_brand'         => $row['campaign_name']['brand'],
                        'fb_init'          => $row['campaign_name']['init'],
                        'fb_camp'          => $row['campaign_name']['camp'],
                        'fb_obj'           => $row['campaign_name']['obj'],
                        'fb_start'         => $row['campaign_name']['Start'],
                        'fb_aud'           => $row['adset_name']['aud'],
                        'fb_bid'           => $row['adset_name']['bid'],
                        'fb_asset'         => $row['ad_name']['asset'],
                        'fb_type'          => $row['ad_name']['type'],
                        'fb_Select your Insurance Provider' => $row['who_is_your_insurance_carrier?'],
                        'roi_attribution_source' => 'FacebookLG'

                    ),
                ),

            );

        }

        return $params;
        
    }

    public function pushToCrm($data){


        dd($data);
    }

    function transformInsurance($insurance){
        $insuranceKey = array(
            'aetna'=> 2,
            'bcbs'=> 13,
            'cigna'=> 17,
            'medicare_(not_accepted)'=> 4,
            'medicaid_(not_accepted)'=> 11,
            'tricare'=> 5,
            'united_healthcare'=> 6,
            'workers_comp'=> 18,
            'other_insurance'=> 7,
            'none'=> 50,
        );

        if(isset($insurance)){
            $result = $insuranceKey[$insurance];
        }
        return $result;
    }

    function matchBrandToVerticalByFormId($formId){
        $verticalKeyByFormId = array(
            '232315910559057'   => ['band_name' => 'NAS', 'vertical' => '9'],
            '256393391064832'   => 'MTCA',
            '375606882785838'   => 'NAS',
            '363893827301949'   => 'NAS', //added 01/13
            '348364945547002'   => 'NAS', //added 01/13
            '213149932440864'   => 'MTCA',
            '1242743042438991'  => 'MTCA',
            '1600693066901975'  => 'MTCA',
            '1216718301708459'  => 'MTCA',
            '191824381266967'   => 'MTCA',
            '243998642682572'   => 'MTCA',
            '1840695919485413'  => 'MTCA', //added 01/13
        );

        return $verticalKeyByFormId[$formId];
    }



    public function doCurlPost($url, $data)
    {



        if($data->patient->vertical_id == '2'){
            $mtcaHeader = 'mtca';
        }else{
            $mtcaHeader = null;
        }





        // Track client IP address
        $client_ip = $_SERVER['REMOTE_ADDR'];
        $data->source->details->client_ip = $client_ip;
        //dd($data->source->details->client_ip);

        $data = http_build_query($data);

        $ch = curl_init($url);

        $headers = array();

        curl_setopt($ch, CURLOPT_POST, count($data));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ["X-CRM-SYSTEM: $mtcaHeader"]);
        //    curl_setopt($ch, CURLOPT_VERBOSE, true);

        $response = curl_exec($ch);

        $httpStatusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $headers = substr($response, 0, $headerSize);
        $response = substr($response, $headerSize);

        // Only get the headers we care about.
        $allHeaders = explode("\n", $headers);
        $headers = array();
        foreach( $allHeaders as $h ) {
            // Ignore empty lines.
            $h = trim($h);
            if( empty($h) ) {
                continue;
            }

            if( ($pos = strpos($h, ':')) !== false && substr($h, 0, 5) != 'HTTP/' ) {
                $headerName = substr($h, 0, $pos);
                $headerValue = trim(substr($h, $pos+1));
                $headers[$headerName] = $headerValue;
            }
            else {
                $headers[] = $h;
            }

        }

        return array($httpStatusCode, $headers, $response);
    }

    public function sendLeadDataToCRM($params)
    {
        $httpStatusCode = $headers = $response = '';



        try {
            // Do the POST.
            list($httpStatusCode, $headers, $response) = $this->doCurlPost(CRM_SERVER_ADDR, $params);

            // We default to checking just the HTTP status code.
            // 200: updated existing
            // 201: created
            // 208: multiple duplicate matches
            $http_success_codes = array(200, 201, 208);
            $result = in_array($httpStatusCode, $http_success_codes, TRUE);

            // Failure.
            if( $result !== true ) {
                throw new \Exception($headers['X-Error-Message']);
            }

            // Success.
            $api_response = 'Success! Form data submitted to CRM.';

        } catch(\Exception $e ) {
            $api_response = 'Failure: ' . $e->getMessage();
        }

        return $result;
    }






}