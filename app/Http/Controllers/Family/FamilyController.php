<?php

namespace App\Http\Controllers\Family;

use App\Http\Controllers\Controller;
use Request;
use Carbon\Carbon;

use App\Models\Tbl_family_list;

class FamilyController extends Controller
{
   public function add_member()
   {
      $family_id                                                  = Tbl_family_list::orderby("family_id","desc")->pluck('family_id')->first() ?? 0;
      $family_id                                                  = $family_id + 1;
      $data                                                       = Request::input('data');

      if($data)
      {
         $insert_father['last_name']                              = $data['father_lastname'];       
         $insert_father['first_name']                             = $data['father_firstname'];         
         $insert_father['middle_name']                            = $data['father_middlename'];          
         $insert_father['birthdate']                              = $data['father_birthdate'];     
         $insert_father['relationship']                           = 'Father';       
         $insert_father['family_id']                              = $family_id;
         $insert_father['created_at']                             = Carbon::now();          

         Tbl_family_list::insert($insert_father);

         $insert_mother['last_name']                              = $data['mother_lastname'];       
         $insert_mother['first_name']                             = $data['mother_firstname'];         
         $insert_mother['middle_name']                            = $data['mother_middlename'];          
         $insert_mother['birthdate']                              = $data['mother_birthdate'];     
         $insert_mother['relationship']                           = 'Mother';       
         $insert_mother['family_id']                              = $family_id;
         $insert_mother['created_at']                             = Carbon::now();          

         Tbl_family_list::insert($insert_mother);

         for ($i=0; $i < $data['number_of_child']; $i++) { 

            $insert_siblings['first_name']                        = $data['siblings_firstname'][$i];   
            $insert_siblings['middle_name']                       = $data['siblings_middlename'][$i];   
            $insert_siblings['last_name']                         = $data['siblings_lastname'][$i];   
            $insert_siblings['birthdate']                         = $data['siblings_birthdate'][$i];   
            $insert_siblings['relationship']                      = 'Siblings';       
            $insert_siblings['family_id']                         = $family_id;
            $insert_siblings['created_at']                        = Carbon::now();    
            Tbl_family_list::insert($insert_siblings);
         }

         $return["status"]                                        = "success"; 
         $return["status_code"]                                   = 200; 
         $return["status_message"]                                = "Added Successfully";

         return $return;
      }
   }
   public static function load_family_list()
   {

      return "nice";
   //  dd("Nice Working", Request::input());

      $response                                                   = Tbl_family_list::where('archive',0)->select('family_id')->groupBy('family_id')->get();

      return $response;
   }
   public static function get_info()
   {
      dd(1231213);
      $family_id                                                  = Request::input('family_id');
      $response                                                   = Tbl_family_list::where('family_id', $family_id)->get();

      return $response;
   }
   public function delete()
   {
      $family_id                                                  = Request::input('family_id');

      $update['archive']                                          = 1;
      Tbl_family_list::where('family_id',$family_id)->update($update);

      $return["status"]                                           = "success"; 
      $return["status_code"]                                      = 200; 
      $return["status_message"]                                   = "Deleted Successfully";

      return $return;
   }
   public function edit()
   {
      $id                                                         = Request::input('id');
      $response                                                   = Tbl_family_list::where('id',$id)->first();

      return $response;
   }
   public function update()
   {
      $id                                                         = Request::input('id');
      $data                                                       = Request::input('data');

      if($data)
      {
         $update['last_name']                                        = $data['last_name'];       
         $update['first_name']                                       = $data['first_name'];         
         $update['middle_name']                                      = $data['middle_name'];          
         $update['birthdate']                                        = $data['birthdate'];    
         $update['updated_at']                                       = Carbon::now();    
         Tbl_family_list::where('id',$id)->update($update);
         
         $return["status"]                                           = "success"; 
         $return["status_code"]                                      = 200; 
         $return["status_message"]                                   = "Deleted Successfully";
         
         return $return;
      }
      
      
   }
   public static function registration()
   {
      $email  = Request::input('email');
      $pass   = Request::input('password');


      // $email = "black".rand(10000, 9999999999)."@disbox.net";
      $birthdate                 = rand(1997, 2006)."-".rand(1, 12)."-".rand(1, 28);
      $birthdate                 = date("Y-m-d", strtotime($birthdate));            
      $user_agent                = Self::random_ua();
      $curl                      = curl_init();
      curl_setopt($curl, CURLOPT_URL, 'https://spclient.wg.spotify.com/signup/public/v2/account/create');
      curl_setopt($curl, CURLOPT_USERAGENT, $user_agent);
      curl_setopt($curl, CURLOPT_HTTPHEADER, array(
      'accept: */*',
      'content-type: application/json',
      'origin: https://www.spotify.com',
      'referer: https://www.spotify.com/',
      'sec-fetch-mode: cors',
      'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/103.0.5060.134 Safari/537.36'));
      curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
      curl_setopt($ch, CURLOPT_PROXY, "35.186.224.25:443");
      // curl_setopt($ch, CURLOPT_PROXYUSERPWD, "user:pass");
      curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
      curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
      curl_setopt($curl, CURLOPT_COOKIEFILE, getcwd().'/cookie.txt');
      curl_setopt($curl, CURLOPT_COOKIEJAR, getcwd().'/cookie.txt');
      curl_setopt($curl, CURLOPT_ENCODING , "gzip");
      curl_setopt($curl, CURLOPT_POSTFIELDS, '{"account_details":{"birthdate":"'.$birthdate.'","consent_flags":{"eula_agreed":true,"send_email":true,"third_party_email":false},"display_name":"MusikaPinas-'.rand(10000, 9999999999).'","email_and_password_identifier":{"email":"'.$email.'","password":"'.$pass.'"},"gender":2},"callback_uri":"https://www.spotify.com/signup/challenge?forward_url=https%3A%2F%2Fopen.spotify.com%2F&locale=ph-en","client_info":{"api_key":"a1e486e2729f46d6bb368d6b2bcda326","app_version":"v2","capabilities":[1],"installation_id":"cbd3dc81-1052-4f26-b801-5a9b9843bb03","platform":"www"},"tracking":{"creation_flow":"","creation_point":"https://www.spotify.com/ph-en/","referrer":""}}');
      $response                    = curl_exec($curl);

      $response = explode('"',$response);

      // {"account_details":{"birthdate":"'.$birthdate.'","consent_flags":{"eula_agreed":true,"send_email":true,"third_party_email":true},"display_name":"MusikaPinas-'.rand(10000, 9999999999).'","email_and_password_identifier":{"email":"'.$email.'","password":"'.$pass.'"},"gender":2},"callback_uri":"https://www.spotify.com/signup/challenge?forward_url=https%3A%2F%2Fopen.spotify.com%2F__noul__%3Fl2l%3D1%26nd%3D1&locale=ca-en","client_info":{"api_key":"a1e486e2729f46d6bb368d6b2bcda326","app_version":"v2","capabilities":[1],"installation_id":"940b919a-bbca-4ec8-817c-54d263139ae4","platform":"www"},"tracking":{"creation_flow":"","creation_point":"https://www.spotify.com/ca-en/api/account/menu/","referrer":"checkout"}}

      if($response[1] == 'error')
      {
         $return['status']             = 'error';
         $return['status_code']        = '500';

         if(count($response) > 23)
         {
            $return['status_message']  =  $response[23];
         }
         else
         {
            $return['status_message']  =  $response[13];
         }
      }
      else
      {
         $return['status']          = 'success';
         $return['status_code']     = '200';
         $return['status_message']  = 'Account is successfully created';
      }
      return $return;
   }

   public static function random_ua() 
   {
      $tiposDisponiveis = array("Chrome", "Firefox", "Opera", "Explorer");
      $tipoNavegador = $tiposDisponiveis[array_rand($tiposDisponiveis)];
      switch ($tipoNavegador) {
          case 'Chrome':
              $navegadoresChrome = array("Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2228.0 Safari/537.36",
                  'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2227.1 Safari/537.36',
                  'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2227.0 Safari/537.36',
                  'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2227.0 Safari/537.36',
                  'Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2226.0 Safari/537.36',
                  'Mozilla/5.0 (Windows NT 6.4; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2225.0 Safari/537.36',
                  'Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2225.0 Safari/537.36',
                  'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2224.3 Safari/537.36',
                  'Mozilla/5.0 (Windows NT 10.0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/40.0.2214.93 Safari/537.36',
                  'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/37.0.2062.124 Safari/537.36',
              );
              return $navegadoresChrome[array_rand($navegadoresChrome)];
              break;
          case 'Firefox':
              $navegadoresFirefox = array("Mozilla/5.0 (Windows NT 6.1; WOW64; rv:40.0) Gecko/20100101 Firefox/40.1",
                  'Mozilla/5.0 (Windows NT 6.3; rv:36.0) Gecko/20100101 Firefox/36.0',
                  'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10; rv:33.0) Gecko/20100101 Firefox/33.0',
                  'Mozilla/5.0 (X11; Linux i586; rv:31.0) Gecko/20100101 Firefox/31.0',
                  'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:31.0) Gecko/20130401 Firefox/31.0',
                  'Mozilla/5.0 (Windows NT 5.1; rv:31.0) Gecko/20100101 Firefox/31.0',
                  'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:29.0) Gecko/20120101 Firefox/29.0',
                  'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:25.0) Gecko/20100101 Firefox/29.0',
                  'Mozilla/5.0 (X11; OpenBSD amd64; rv:28.0) Gecko/20100101 Firefox/28.0',
                  'Mozilla/5.0 (X11; Linux x86_64; rv:28.0) Gecko/20100101 Firefox/28.0',
              );
              return $navegadoresFirefox[array_rand($navegadoresFirefox)];
              break;
          case 'Opera':
              $navegadoresOpera = array("Opera/9.80 (Windows NT 6.0) Presto/2.12.388 Version/12.14",
                  'Opera/9.80 (X11; Linux i686; Ubuntu/14.10) Presto/2.12.388 Version/12.16',
                  'Mozilla/5.0 (Windows NT 6.0; rv:2.0) Gecko/20100101 Firefox/4.0 Opera 12.14',
                  'Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.0) Opera 12.14',
                  'Opera/12.80 (Windows NT 5.1; U; en) Presto/2.10.289 Version/12.02',
                  'Opera/9.80 (Windows NT 6.1; U; es-ES) Presto/2.9.181 Version/12.00',
                  'Opera/9.80 (Windows NT 5.1; U; zh-sg) Presto/2.9.181 Version/12.00',
                  'Opera/12.0(Windows NT 5.2;U;en)Presto/22.9.168 Version/12.00',
                  'Opera/12.0(Windows NT 5.1;U;en)Presto/22.9.168 Version/12.00',
                  'Mozilla/5.0 (Windows NT 5.1) Gecko/20100101 Firefox/14.0 Opera/12.0',
              );
              return $navegadoresOpera[array_rand($navegadoresOpera)];
              break;
          case 'Explorer':
              $navegadoresOpera = array("Mozilla/5.0 (Windows NT 6.1; WOW64; Trident/7.0; AS; rv:11.0) like Gecko",
                  'Mozilla/5.0 (compatible, MSIE 11, Windows NT 6.3; Trident/7.0; rv:11.0) like Gecko',
                  'Mozilla/1.22 (compatible; MSIE 10.0; Windows 3.1)',
                  'Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; WOW64; Trident/5.0; .NET CLR 3.5.30729; .NET CLR 3.0.30729; .NET CLR 2.0.50727; Media Center PC 6.0)',
                  'Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 7.0; InfoPath.3; .NET CLR 3.1.40767; Trident/6.0; en-IN)',
              );
              return $navegadoresOpera[array_rand($navegadoresOpera)];
              break;
      }
  }
}
