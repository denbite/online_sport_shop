<?php


namespace app\components\novaposhta;


use app\models\Config;
use Yii;
use yii\base\BaseObject;
use yii\db\Exception;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

class NovaPoshta
    extends BaseObject
{
    
    const HTTP_POST = 'POST';
    
    const HTTP_GET = 'GET';
    
    const CACHE_CITIES_ARRAY = 'cache_cities_array';
    
    const CACHE_WAREHOUSES_ARRAY = 'cache_warehouses_array';
    
    const CACHE_QUERY_DEFAULT = 'cache_novaposhta';
    
    private $api_key;
    
    public function init()
    {
        parent::init();
        
        $this->initApiKey();
    }
    
    private function initApiKey()
    {
        $key = Config::findOne([ 'name' => 'nova_poshta_api_key' ])->value;
        
        if (!empty($key) and is_string($key)) {
            $this->api_key = $key;
        } else {
            throw new Exception('Пожалуйста, добавьте Нова Пошта API key в настройках');
        }
    }
    
    public function getCitiesArray($city_name)
    {
        $result = [];
    
        foreach ($this->getCities($city_name) as $city) {
            if ($city['Warehouses'] > 0) {
                $result[$city['DeliveryCity']] = $city['Present'];
            }
        }
    
        return $result;
        
    }
    
    public function getCities($city_name)
    {
        if (mb_strlen($city_name) >= 3) {
            $model = 'Address';
            $method = 'searchSettlements';
    
            $params['CityName'] = $city_name;
            
            $response = $this->sendRequest($model, $method, $params);
            
        }
        
        return !empty($response[0]['Addresses']) ? $response[0]['Addresses'] : [];
    }
    
    private function sendRequest($model, $method, array $params = [], $http = self::HTTP_POST)
    {
        // todo-cache: add cache (many time, by model+method+params+http)
        return Yii::$app->cache->getOrSet(self::CACHE_QUERY_DEFAULT . "-{$model}-{$method}-" . serialize($params) . "-{$http}",
            function () use ($model, $method, $params, $http)
            {
            
                $param = '';
            
                if (!empty($params) and is_array($params)) {
                    foreach ($params as $key => $value) {
                        if (!empty($param)) {
                            $param .= ',';
                        }
                        $param .= "\"$key\":\"$value\"";
                    }
                }
            
                $curl = curl_init();
                curl_setopt_array($curl, [
                    CURLOPT_URL => "https://api.novaposhta.ua/v2.0/json/",
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => $http,
                    CURLOPT_POSTFIELDS => "{\r\n\"apiKey\": \"\",\r\n\"modelName\": \"$model\",\r\n\"calledMethod\": \"$method\",\r\n\"methodProperties\": {{$param}}\r\n}",
                    CURLOPT_HTTPHEADER => [ "content-type: application/json", ],
                ]);
            
                $response = curl_exec($curl);
                $err = curl_error($curl);
                curl_close($curl);
                if ($err) {
                    throw new \Exception($err);
                }
            
                $response = Json::decode($response);
            
                return $response['success'] ? $response['data'] : null;
            }, 3600 * 24 * 7);
    }
    
    public function getWarehousesArray($cityRef = null)
    {
        return ArrayHelper::map($this->getWarehouses($cityRef), 'Ref', 'DescriptionRu');
    }
    
    public function getWarehouses($cityRef = null, $warehouseRef = null)
    {
        $model = 'Address';
        $method = 'getWarehouses';
        $params = [
            'Language' => 'ru',
        ];
        
        if (!empty($cityRef)) {
            $params['CityRef'] = $cityRef;
        }
        
        if (!empty($warehouseRef)) {
            $params['Ref'] = $warehouseRef;
        }
        
        $response = $this->sendRequest($model, $method, $params);
        
        return !empty($response) ? $response : [];
    }
    
    public function getCityNameByRef($ref, $lang = 'ua')
    {
        $data = $this->getCityByRef($ref);
        
        if (!empty($data) and is_array($data) and array_key_exists('Description',
                                                                   $data) and array_key_exists('DescriptionRu',
                                                                                               $data)) {
            switch ($lang) {
                case 'ua':
                    return $data['Description'];
                case 'ru':
                    return $data['DescriptionRu'];
                default:
                    break;
            }
        }
        
        return null;
    }
    
    public function getCityByRef($ref)
    {
        if (!empty($ref) and is_string($ref)) {
            $model = 'Address';
            $method = 'getCities';
            
            $params['Ref'] = $ref;
            
            $response = $this->sendRequest($model, $method, $params);
            
        }
        
        return !empty($response[0]) ? $response[0] : [];
    }
    
    public function getWarehouseNameByRef($ref, $lang = 'ua')
    {
        $data = $this->getWarehouses(null, $ref);
        
        if (!empty($data[0]) and is_array($data[0]) and array_key_exists('Description',
                                                                         $data[0]) and array_key_exists('DescriptionRu',
                                                                                                        $data[0])) {
            switch ($lang) {
                case 'ua':
                    return $data[0]['Description'];
                case 'ru':
                    return $data[0]['DescriptionRu'];
                default:
                    break;
            }
        }
        
        return null;
    }
}