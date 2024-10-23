<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;

class tapController extends Controller
{
    public function index()
    {
        //cus_TS07A0320240859Ru7b1610970
        //tok_JHm43624612bc6n16CF9k843
        //card_dkj73624612tYKF16l69U853
        return view('tap');
    }
    public function customer()
    {
        $client = new \GuzzleHttp\Client();

        $response = $client->request('POST', 'https://api.tap.company/v2/customers', [
            'body' => '{"first_name":"test2","middle_name":"middlename","last_name":"lastname","email":"test@test.com","phone":{"country_code":"965","number":"51234567"},"description":"test","metadata":{"sample string 1":"string1","sample string 3":"string2"},"currency":"SAR"}',
            'headers' => [
                'Authorization' => 'Bearer sk_test_QHc3zRUrNPDBokJatnFf9luh',
                'accept' => 'application/json',
                'content-type' => 'application/json',
            ],
        ]);
        $responseBody = json_decode($response->getBody(), true);
        dd($responseBody);

        /*if ($response->successful()) {
            // Retrieve the customer ID from the API response
            $customerId = $response->json()['id'];

            // Return the customer ID (you can save it in the database or use it directly)
            return response()->json(['customer_id' => $customerId, 'message' => 'Customer created successfully'], 200);
        } else {
            // Handle error
            return response()->json(['error' => 'Customer creation failed', 'details' => $response->json()], 500);
        }*/

    }

    public function checkOut()
    {
        $client = new Client();

        $response = $client->request('POST', 'https://api.tap.company/v2/charges/', [
            'body' => '{"amount":1,"currency":"SAR",
            "customer_initiated":true,
            "threeDSecure":true,
            "save_card":false,
            "description":"Test Description",
            "metadata":{"udf1":"Metadata 1"},
            "reference":{"transaction":"txn_01","order":"ord_01"},
            "receipt":{"email":true,"sms":true},
            "customer":{"first_name":"testcus","middle_name":"testcus","last_name":"testcus",
            "email":"test1@test.com","phone":{"country_code":965,"number":51234568}},
            "merchant":{"id":"1234"},
            "source":{"id":"src_all"},
            "post":{"url":"https://webhook.site/b8be9951-8011-40d1-b1b4-41debfa145e4"},
            "redirect":{"url":"https://paytab-app.test/"}}',
            'headers' => [
                'Authorization' => 'Bearer sk_test_QHc3zRUrNPDBokJatnFf9luh',
                'accept' => 'application/json',
                'content-type' => 'application/json',
            ],
        ]);

        $responseBody = json_decode($response->getBody(), true);

        if ($responseBody['status'] == 'INITIATED') {
            return redirect($responseBody['transaction']['url']);  // Redirect to the Tap Payment page
        }
        return back()->with('error', 'Something went wrong!');
    }
    public function createToken()
    {

        $client = new \GuzzleHttp\Client();

        $response = $client->request('POST', 'https://api.tap.company/v2/tokens', [
            'body' => '{"saved_card":{"card_id":"card_dkj73624612tYKF16l69U853","customer_id":"cus_TS07A0320240859Ru7b1610970"},"client_ip":"127.0.0.1"}',
            'headers' => [
                'Authorization' => 'Bearer sk_test_QHc3zRUrNPDBokJatnFf9luh',
                'accept' => 'application/json',
                'content-type' => 'application/json',
            ],
        ]);

        dd($response->getBody());
    }
    public function retrieveCard()
    {

        $client = new \GuzzleHttp\Client();
        $cusId = 'cus_TS07A0320240859Ru7b1610970';
        $cardId = 'card_dkj73624612tYKF16l69U853';
        $response = $client->request('GET', 'https://api.tap.company/v2/card/'.$cusId.'/'.$cardId, [
            'headers' => [
                'Authorization' => 'Bearer sk_test_XKokBfNWv6FIYuTMg5sLPjhJ',
                'accept' => 'application/json',
            ],
        ]);
        dd($response->getBody());

    }
}
