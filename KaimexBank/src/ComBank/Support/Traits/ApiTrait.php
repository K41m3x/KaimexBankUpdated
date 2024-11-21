<?php
namespace ComBank\Support\Traits;
use ComBank\Transactions\Contracts\BankTransactionInterface;
trait ApiTrait
{
    function convertBalance(float $balance): float
    {
        $apiUrl = "https://api.exchangerate-api.com/v4/latest/EUR";

        // Obtener la respuesta de la API
        $response = file_get_contents($apiUrl);

        // Decodificar la respuesta JSON
        $data = json_decode($response, true);

        // Obtener la tasa de cambio para USD y convertir
        return $balance * $data['rates']['USD'];
    }

    function validateEmail(string $email): bool
    {
        $curl = curl_init();

        $data = [
            'email' => $email,
        ];

        $post_data = http_build_query($data);

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://disify.com/api/email",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $post_data,
        ));

        $response = json_decode(curl_exec($curl), true);

        curl_close($curl);

        if ($response['format'] && !$response['disposable'] && $response["dns"]) {
            return true;
        } else {
            return false;
        }
    }

    function detectFraud(BankTransactionInterface $bankTransaction): bool
    {
        $api = "https://673cc4b896b8dcd5f3fb8cdc.mockapi.io/fraud/transaction";
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $api,
            CURLOPT_RETURNTRANSFER => true,
        ]);

        $response = curl_exec($curl);

        $fraudData = json_decode($response, true);

        // Obtener información de la transacción
        $transactionType = $bankTransaction->getTransactionInfo();
        $transactionAmount = $bankTransaction->getAmount();

        // Recorrer los datos de la API
        $condition = false;
        foreach ($fraudData as $key => $fraudTransaction) {
            if ($fraudData[$key]['Type'] == $transactionType) {
                if ($fraudData[$key]['Amount'] < $transactionAmount) {
                    if ($fraudData[$key]['Action'] == true) {
                        $condition = true;
                    
                    }
                }
            }
        }
        
        curl_close($curl);
        return $condition;
       
    }
    function passGeneration(): string {
        $longitud = isset($_GET['longitud']) ? (int)$_GET['longitud'] : 12;
    
        if ($longitud < 4 || $longitud > 64) {
            return json_encode(['error' => 'La longitud debe estar entre 4 y 64 caracteres.']);
        }
    
        $url = 'https://www.random.org/strings/?num=1&len=' . $longitud . '&digits=on&upperalpha=on&loweralpha=on&unique=off&format=plain&rnd=new';
    
        // Consumir API con cURL
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $respuesta = curl_exec($ch);
        curl_close($ch);
    
        // Devolver la contraseña como JSON
        return json_encode(['Password: ' => trim($respuesta)]);
    }
}