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
   public function load_family_list()
   {
      $response                                                   = Tbl_family_list::where('archive',0)->select('family_id')->groupBy('family_id')->get();

      return $response;
   }
   public function get_info()
   {
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
