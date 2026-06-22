<?php

require_once __DIR__ . '/env.php';



class Supabase
{


    private $url;

    private $key;



    public function __construct()
    {


        $this->url = getenv("SUPABASE_URL");

        $this->key = getenv("SUPABASE_KEY");



        if (!$this->url || !$this->key) {

            die("Erro: Supabase não configurado.");

        }


    }





    private function request($endpoint, $method="GET", $data=null)
    {


        $curl = curl_init();


        curl_setopt_array($curl,[


            CURLOPT_URL =>
            $this->url . "/rest/v1/" . $endpoint,


            CURLOPT_RETURNTRANSFER => true,


            CURLOPT_CUSTOMREQUEST => $method,


            CURLOPT_HTTPHEADER => [


                "apikey: ".$this->key,


                "Authorization: Bearer ".$this->key,


                "Content-Type: application/json",


                "Prefer: return=representation"

            ]

        ]);





        if($data){


            curl_setopt(

                $curl,

                CURLOPT_POSTFIELDS,

                json_encode($data)

            );


        }




        $response = curl_exec($curl);


        curl_close($curl);



        return json_decode($response,true);


    }







    public function get($table,$params="")
    {

        return $this->request(
            $table . $params
        );

    }







    public function insert($table,$data)
    {

        return $this->request(

            $table,

            "POST",

            $data

        );

    }






    public function update($table,$id,$data)
    {


        return $this->request(

            $table . "?id=eq.".$id,

            "PATCH",

            $data

        );


    }






    public function delete($table,$id)
    {


        return $this->request(

            $table . "?id=eq.".$id,

            "DELETE"

        );


    }



}


?>
