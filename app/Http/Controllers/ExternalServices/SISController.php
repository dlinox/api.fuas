<?php

namespace App\Http\Controllers\ExternalServices;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SISController extends Controller
{
    protected $baseURL;
    protected $domain;

    public function __construct()
    {
        $this->baseURL = 'http://app3.sis.gob.pe/SisConsultaEnLinea/Consulta/';
        $this->domain = 'http://app3.sis.gob.pe';
    }

    public function getPersonalData($sessionId)
    {
        try {

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => "{$this->baseURL}frmResultadoEnLinea.aspx",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                    "cookie: ASP.NET_SessionId={$sessionId}",
                    "origin: {$this->domain}",
                    "referer: {$this->baseURL}frmConsultaEnLinea.aspx",
                ),
            ));
            $response = curl_exec($curl);

            curl_close($curl);

            $dom = new \DOMDocument();
            @$dom->loadHTML($response);

            $xpath = new \DOMXPath($dom);
            $table = $xpath->query('//table[@id="dgConsulta"]')->item(0);

            if (!$table) {
                throw new \Exception('No hay resultados en la Web de consultas del SIS');
            }

            $data = [];

            $row = $xpath->query('.//tr[@class="c_texto_02"]', $table)->item(0);

            // $data['affiliationNumber'] = trim($xpath->query('.//td[3]', $row)->item(0)->textContent);
            $data['documentNumber'] = trim($xpath->query('.//td[4]', $row)->item(0)->textContent);
            $data['paternalLastName'] = trim($xpath->query('.//td[5]', $row)->item(0)->textContent);
            $data['maternalLastName'] = trim($xpath->query('.//td[6]', $row)->item(0)->textContent);
            $data['name'] = trim($xpath->query('.//td[7]', $row)->item(0)->textContent);
            // $data['insuredType'] = trim($xpath->query('.//td[8]', $row)->item(0)->textContent);
            // $data['status'] = trim($xpath->query('.//td[9]', $row)->item(0)->textContent);

            return $data;
        } catch (\Exception $e) {

            throw new \Exception("Error al obtener los datos del SIS");
        }
    }

    public function getDetailsData($sessionId)
    {
        try {
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => "{$this->baseURL}FrmDetalleEnLinea.aspx",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                    "cookie: ASP.NET_SessionId={$sessionId}",
                    "origin: {$this->domain}",
                    "referer: {$this->baseURL}frmConsultaEnLinea.aspx",
                ),
            ));
            $response = curl_exec($curl);

            curl_close($curl);

            $data = [];

            $dom = new \DOMDocument();
            @$dom->loadHTML($response);

            $xpath = new \DOMXPath($dom);
            $table = $xpath->query('//table[@id="Table1"]')->item(0);

            if (!$table) {
                throw new \Exception('No hay detalles de la cnosulta en la Web de consultas del SIS');
            }

            $document =  trim($xpath->query('.//td[contains(text(), "Documento de Identidad")]/following-sibling::td', $table)->item(0)->textContent);

            $documentType = explode(' ', $document)[0];            
            $affiliationNumber = trim($xpath->query('.//span[@id="ltcNroAfil"]', $table)->item(0)->textContent);
            $insuredType = trim($xpath->query('.//td[contains(text(), "Tipo de asegurado")]/following-sibling::td', $table)->item(0)->textContent);
            $insuranceType  = trim($xpath->query('.//td[contains(text(), "Tipo de seguro")]/following-sibling::td', $table)->item(0)->textContent);
            $formatType  = trim($xpath->query('.//td[contains(text(), "Tipo de formato")]/following-sibling::td', $table)->item(0)->textContent);
            $affiliationDate  = trim($xpath->query('.//td[contains(text(), "Fecha de afiliación")]/following-sibling::td', $table)->item(0)->textContent);
            $benefitsPlan  = trim($xpath->query('.//td[contains(text(), "Plan de Beneficios")]/following-sibling::td', $table)->item(0)->textContent);
            $healthFacility  = trim($xpath->query('.//td[contains(text(), "Establecimiento de salud")]/following-sibling::td', $table)->item(0)->textContent);
            $facilityLocation = trim($xpath->query('.//td[contains(text(), "Ubicación de establecimiento de salud")]/following-sibling::td', $table)->item(0)->textContent);
            $status = trim($xpath->query('.//td[contains(text(), "Estado")]/following-sibling::td', $table)->item(0)->textContent);
            

            $data['documentType'] = utf8_decode($documentType);
            $data['affiliationNumber'] = utf8_decode($affiliationNumber);
            $data['insuredType'] = utf8_decode($insuredType);
            $data['insuranceType'] = utf8_decode($insuranceType);
            $data['formatType'] = utf8_decode($formatType);
            $data['affiliationDate'] = utf8_decode($affiliationDate);
            $data['benefitsPlan'] = utf8_decode($benefitsPlan);
            $data['healthFacility'] = utf8_decode($healthFacility);
            $data['facilityLocation'] = utf8_decode($facilityLocation);
            $data['status'] = utf8_decode($status);

            return $data;
        } catch (\Exception $e) {
            throw new \Exception("Error al obtener los detalles de la consulta en el SIS");
        }
    }
}
