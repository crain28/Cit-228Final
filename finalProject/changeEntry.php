<?php 
session_start(); 
include 'connect.php';
 doDB();

if (!$_POST)  {  
//haven't seen the selection form, so show it  
  $display_block = "<h1>Select an Entry to Update</h1>";

//get parts of records  $get_list_sql = "SELECT id,                   
  CONCAT_WS(', ', l_name, f_name) AS display_name                   
  FROM master_name ORDER BY l_name, f_name";  
  $get_list_res = mysqli_query($mysqli, $get_list_sql) or die(mysqli_error($mysqli));
  
  if (mysqli_num_rows($get_list_res) < 1) {    
      //no records    
      $display_block .= "<p><em>Sorry, no records to select!</em></p>";
  } 
  else {    
    //has records, so get results and print in a form    
    $display_block .= "<form method=\"post\" action=\"".$_SERVER['PHP_SELF']."\">   
     <p><label for=\"change_id\">Select a Record to Update:</label><br/>   
      <select id=\"change_id\" name=\"change_id\" required=\"required\">   
      <option value=\"\">-- Select One --</option>";
    while ($recs = mysqli_fetch_array($get_list_res)) {      
        $id = $recs['id'];      
        $display_name = stripslashes($recs['display_name']);      
        $display_block .= "<option value=\"".$id."\">".$display_name."</option>";   
    }
    $display_block .= "</select></p><button class='btn-success' type='submit' name='submit' 
    value='change'>Change Selected Entry</button>   
     </form>";  }  
     
     //free result  
     mysqli_free_result($get_list_res);
} 
else if ($_POST) {  
    //check for required fields  
if ($_POST['change_id'] == "")  {    
    header("Location: changeEntry.php");    
    exit;  
}
  //create safe version of ID  
  $safe_id = mysqli_real_escape_string($mysqli, $_POST['change_id']);  
  $_SESSION["id"]=$safe_id;  
  $_SESSION["address"]="true";  
  $_SESSION["telephone"]="true";  
  $_SESSION["email"]="true";  
  $_SESSION["fax"]="true";  
  $_SESSION["notes"]="true";  

//get master_info  
  $get_master_sql = "SELECT f_name, l_name FROM master_name WHERE id = 
  '".$safe_id."'";
   $get_master_res = mysqli_query($mysqli, $get_master_sql) or 
   die(mysqli_error($mysqli));

   while ($name_info = mysqli_fetch_array($get_master_res)) {    
       $display_fname = stripslashes($name_info['f_name']);    
       $display_lname = stripslashes($name_info['l_name']);    
      }

   $display_block = "<h1>Record Update</h1>";  
   $display_block.="<form method='post' action='change.php'>";  
   $display_block.="<fieldset><label>First/Last Names:</label><br/>";  
   $display_block.="<input type='text' name='f_name' size='20' maxlength='75' 
   required='required' value='" . $display_fname . "'/>";
   $display_block.="<input type='text' name='l_name' size='30' maxlength='75' 
   required='required' value='" . $display_lname . "'/></fieldset>";  

//free result  
   mysqli_free_result($get_master_res);  
//get all addresses  

   $get_addresses_sql = "SELECT address, city, state, zipcode, type                        
   FROM address WHERE master_id = '".$safe_id."'";  
   $get_addresses_res = mysqli_query($mysqli, $get_addresses_sql) or 
   die(mysqli_error($mysqli));

   if (mysqli_num_rows($get_addresses_res) > 0) {
    $display_block .= "<p><strong>Addresses:</strong></p>";

   while ($add_info = mysqli_fetch_array($get_addresses_res)) {   
    $address = stripslashes($add_info['address']);      
    $city = stripslashes($add_info['city']);      
    $state = stripslashes($add_info['state']);      
    $zipcode = stripslashes($add_info['zipcode']);      
    $address_type = $add_info['type'];

    $display_block .="<p><label for='address'>Street Address:</label><br/>";
    $display_block .="<input type='text' id='address' name='address' size='30' 
value='".$address."'/></p>";      
    $display_block .="<fieldset><label>City/State/Zip:</label><br/>";      
    $display_block .="<input type='text' name='city' size='30' maxlength='50' 
value='" . $city . "'/>";      
    $display_block .="<input type='text' name='state' size='5' maxlength='2' 
value='".$state."'/>";      
    $display_block .="<input type='text' name='zipcode' size='10' maxlength='10' 
value='".$zipcode."'/></fieldset>";      
    $display_block .="<fieldset><label>Address Type:</label><br/>";      
   if ($address_type=="home"){        
    $display_block .="<input type='radio' id='add_type_h' name='add_type' 
value='home' checked='checked' /><label for='add_type_h'>home</label>";  
    $display_block .="<input type='radio' id='add_type_w' name='add_type' 
value='work' /><label for='add_type_w'>work</label>";        
    $display_block .="<input type='radio' id='add_type_o' name='add_type' 
value='other' /><label for='add_type_o'>other</label>";     
    }     
   else if ($address_type=="work"){       
    $display_block .="<input type='radio' id='add_type_h' name='add_type'
value='home'  /><label for='add_type_h'>home</label>";        
    $display_block .="<input type='radio' id='add_type_w' name='add_type' 
value='work' checked='checked'/><label for='add_type_w'>work</label>";      
    $display_block .="<input type='radio' id='add_type_o' name='add_type' 
    value='other' /><label for='add_type_o'>other</label>";
   }
   else{        
       $display_block .="<input type='radio' id='add_type_h' name='add_type' 
value='home' /><label for='add_type_h'>home</label>";        
    $display_block .="<input type='radio' id='add_type_w' name='add_type' 
value='work' /><label for='add_type_w'>work</label>";        
    $display_block .="<input type='radio' id='add_type_o' name='add_type' 
value='other' checked='checked' /><label for='add_type_o'>other</label>";  
    }     
  }  
    $display_block .="</fieldset>";  
    }  
   else{  
    $_SESSION["address"]='false';  
    $display_block .= <<<END_OF_BLOCK  
    <p><label for="address">Street Address:</label><br/>  
    <input type="text" id="address" name="address" size="30" /></p>
    <fieldset>  
    <label>City/State/Zip:</label><br/>  
    <input type="text" name="city" size="30" maxlength="50" />  
    <input type="text" name="state" size="5" maxlength="2" /> 
     <input type="text" name="zipcode" size="10" maxlength="10" />  
     </fieldset>    

     <fieldset> 
     <label><br>Address Type:</label><br/>  
     <input type="radio" id="add_type_h" name="add_type" value="home" checked />      
        <label for="add_type_h">home</label>  
     <input type="radio" id="add_type_w" name="add_type" value="work" />    
        <label for="add_type_w">work</label> 
     <input type="radio" id="add_type_o" name="add_type" value="other" /> 
        <label for="add_type_o">other</label> 
     </fieldset> 
     END_OF_BLOCK;  
    }

//free result  
    mysqli_free_result($get_addresses_res);

//get all tel  
    $get_tel_sql = "SELECT tel_number, type FROM telephone            
    WHERE master_id = '".$safe_id."'";
      $get_tel_res = mysqli_query($mysqli, $get_tel_sql) or die(mysqli_error($mysqli));
    
      if (mysqli_num_rows($get_tel_res) > 0) {    
          $display_block .= "<p><strong>Telephone:</strong></p>";

      while ($tel_info = mysqli_fetch_array($get_tel_res)) {     
            $tel_number = stripslashes($tel_info['tel_number']);      
            $tel_type = $tel_info['type'];

            $display_block .="<fieldset><label>Telephone Number:</label><br/>";      
            $display_block .="<input type='text' name='tel_number' size='30' maxlength='25' 
            value='".$tel_number."'/>";      
            if ($tel_type=="home"){        
                $display_block .="<input type='radio' id='tel_type_h' name='tel_type' 
                value='home' checked='checked' /><label for='tel_type_h'>home</label>";
                $display_block .="<input type='radio' id='tel_type_w' name='tel_type' 
                value='work' /><label for='tel_type_w'>work</label>";

                $display_block .="<input type='radio' id='tel_type_o' name='tel_type'
                value='other' /><label for='tel_type_o'>other</label>";      }     
                 else if ($tel_type=="work") {        
                     $display_block .="<input type='radio' id='tel_type_h' name='tel_type' 
                     value='home'  /><label for='tel_type_h'>home</label>";        
                     $display_block .="<input type='radio' id='tel_type_w' name='tel_type' 
                     value='work' checked='checked' /><label for='tel_type_w'>work</label>";       
                     $display_block .="<input type='radio' id='tel_type_o' name='tel_type' 
                     value='other' /><label for='tel_type_o'>other</label>";      }      
                     else{        
                     $display_block .="<input type='radio' id='tel_type_h' name='tel_type' 
                     value='home' /><label for='tel_type_h'>home</label>";        
                     $display_block .="<input type='radio' id='tel_type_w' name='tel_type' 
                     value='work' /><label for='tel_type_w'>work</label>";        
                     $display_block .="<input type='radio' id='tel_type_o' name='tel_type' 
                     value='other' checked='checked' /><label for='tel_type_o'>other</label>";     
                     }    
                    }  
                $display_block .="</fieldset>";
            }
            else{  
                $_SESSION["telephone"]='false';   
                $display_block .= <<<END_OF_BLOCK  
                <fieldset>  
                <label>Telephone Number:</label><br/>  
                <input type="text" name="tel_number" size="30" maxlength="25" />  
                <input type="radio" id="tel_type_h" name="tel_type" value="home" checked />
                    <label for="tel_type_h">home</label>  
                <input type="radio" id="tel_type_w" name="tel_type" value="work" />     
                    <label for="tel_type_w">work</label>  
                <input type="radio" id="tel_type_o" name="tel_type" value="other" />
                    <label for="tel_type_o">other</label>  
                    </fieldset> 
                    END_OF_BLOCK;  
                }  
                //free result  
                mysqli_free_result($get_tel_res);
                //get all fax  
                $get_fax_sql = "SELECT fax_number, type FROM fax   
                WHERE master_id = '".$safe_id."'";  
                $get_fax_res = mysqli_query($mysqli, $get_fax_sql) or die(mysqli_error($mysqli));
  if (mysqli_num_rows($get_fax_res) > 0) {    
      $display_block .= "<p><strong>Fax:</strong><br/></p>";    
      while ($fax_info = mysqli_fetch_array($get_fax_res)) {      
          $fax_number =  stripslashes($fax_info['fax_number']);      
          $fax_type = $fax_info['type'];      
          $display_block.="<fieldset> <label>Fax Number:</label><br/>";      
          $display_block.="<input type='text' name='fax_number' size='30' maxlength='25' 
          value='" . $fax_number . "'/>";      
          if ($fax_type=="home"){        
              $display_block.="<input type='radio' id='fax_type_h' name='fax_type' 
              value='home' checked='checked' /><label for='ax_type_h'>home</label>";        
              $display_block.="<input type='radio' id='fax_type_w' name='fax_type' 
              value='work' /><label for='fax_type_w'>work</label>";        
              $display_block.="<input type='radio' id='fax_type_o' name='fax_type' 
              value='other' /><label for='fax_type_o'>other</label>";
          }
          else if ($fax_type=="work"){        
              $display_block.="<input type='radio' id='fax_type_h' name='fax_type' 
              value='home'  /><label for='ax_type_h'>home</label>";        
              $display_block.="<input type='radio' id='fax_type_w' name='fax_type'
               value='work' checked='checked'/><label for='fax_type_w'>work</label>"; 
              $display_block.="<input type='radio' id='fax_type_o' name='fax_type' 
              value='other' /><label for='fax_type_o'>other</label>";     
             }      
             else{        
                 $display_block.="<input type='radio' id='fax_type_h' name='fax_type' 
                 value='home'  /><label for='ax_type_h'>home</label>";        
                 $display_block.="<input type='radio' id='fax_type_w' name='fax_type' 
                 value='work' /><label for='fax_type_w'>work</label>";        
                 $display_block.="<input type='radio' id='fax_type_o' name='fax_type' 
                 value='other' checked='checked'/><label for='fax_type_o'>other</label>";     
                 }     
                }    
          $display_block.="</fieldset>";  
        }
        else{    $_SESSION["fax"]='false';    
            $display_block .= <<<END_OF_BLOCK  
            <label>Fax Number:</label><br/>  
            <input type="text" name="fax_number" size="30" maxlength="25" /> 
             <input type="radio" id="fax_type_h" name="fax_type" value="home" checked />      
             <label for="fax_type_h">home</label>  
             <input type="radio" id="fax_type_w" name="fax_type" value="work" />      
             <label for="fax_type_w">work</label>  
             <input type="radio" id="fax_type_o" name="fax_type" value="other" />      
             <label for="fax_type_o">other</label>  
             </fieldset>

            END_OF_BLOCK;
        }
         //free result  
         mysqli_free_result($get_fax_res);
        //get all email  
        $get_email_sql = "SELECT email, type FROM email                    
        WHERE master_id = '".$safe_id."'";  
        $get_email_res = mysqli_query($mysqli, $get_email_sql) or 
        die(mysqli_error($mysqli));   
        if (mysqli_num_rows($get_email_res) > 0) {    
            $display_block .= "<p><strong>Email:</strong></p>";

        while ($email_info = mysqli_fetch_array($get_email_res)) {      
        $email = stripslashes($email_info['email']);      
        $email_type = $email_info['type'];      
        $display_block .= "<fieldset><label>Email Address:</label><br/>";      
        $display_block .= "<input type='email' name='email' size='30' maxlength='150' 
        value='".$email."' />";
        if ($email_type=="home"){        
        $display_block .= "<input type='radio' id='email_type_h' name='email_type' 
        value='home' checked='checked' /><label for='email_type_h'>home</label>";          
        $display_block .= "<input type='radio' id='email_type_w' name='email_type'
        value='work' /><label for='email_type_w'>work</label>";          
        $display_block .= "<input type='radio' id='email_type_o' name='email_type' 
        value='other' /><label for='email_type_o'>other</label>";
    } else if ($email_type=="work"){        
        $display_block .= "<input type='radio' id='email_type_h' name='email_type'
        value='home'  /><label for='email_type_h'>home</label>";          
        $display_block .= "<input type='radio' id='email_type_w' name='email_type'
        value='work' checked='checked'/><label for='email_type_w'>work</label>";          
        $display_block .= "<input type='radio' id='email_type_o' name='email_type'
        value='other' /><label for='email_type_o'>other</label>";        
    } 
    else{        
        $display_block .= "<input type='radio' id='email_type_h' name='email_type' 
        value='home'  /><label for='email_type_h'>home</label>";          
        $display_block .= "<input type='radio' id='email_type_w' name='email_type'
         value='work' /><label for='email_type_w'>work</label>";     
        $display_block .= "<input type='radio' id='email_type_o' name='email_type'
         value='other' checked='checked'/><label for='email_type_o'>other</label>";       
         }    
        }
    $display_block .= "</fieldset>"; 
 } 
 else{  
    $_SESSION["email"]='false';  $display_block .= '<fieldset><label>Email Address:</label><br/><input type="email" 
    name="email" size="30" maxlength="150" />  <input type="radio" id="email_type_h" 
    name="email_type" value="home" checked />'; 
    $display_block.= '<label for="email_type_h">home</label><input type="radio" id="email_type_w" name="email_type" 
    value="work" /><label for="email_type_w">work</label>';  
    $display_block.='<input type="radio" id="email_type_o" name="email_type" 
    value="other" /><label for="email_type_o">other</label></fieldset>';  }   
      
    //free result  
    mysqli_free_result($get_email_res);

 //get personal note  
 $get_notes_sql = "SELECT note FROM personal_notes                    
 WHERE master_id = '".$safe_id."'";  
 $get_notes_res = mysqli_query($mysqli, $get_notes_sql) or die(mysqli_error($mysqli));
 if (mysqli_num_rows($get_notes_res) == 1) {    
     while ($note_info = mysqli_fetch_array($get_notes_res)) {      
         $note = nl2br(stripslashes($note_info['note']));   
         }    
        $display_block .= "<p><label for='note'>Personal Note:</label><br/>";   
        $display_block .= "<textarea id='note' name='note' cols='35' rows='3'>".$note." </textarea></p>";  } 
        else{ 
        $_SESSION["notes"]='false';  
        $display_block .= '<p><label for="note">Personal Note:</label><br/>
        <textarea id="note" name="note" cols="35" rows="3"></textarea></p>';  }    
    
        //free result 
         mysqli_free_result($get_notes_res);

        $display_block .= "<p style='text-align: left;'><button class='btn-success' 
        type='submit' name='submitChange' id='submitChange' value='submitChange'>Change Entry</button>";
        $display_block .= "&nbsp;&nbsp;&nbsp;&nbsp;<a href='addressBookMenu.html' 
        style='color:darkgreen';>Cancel and return to main menu</a></p></form>"; } 
        //close connection to MySQL 
        mysqli_close($mysqli);
?> 
<?php include 'BeginNav.php'; ?>
<?php echo $display_block; ?> 
<?php include 'EndNav.php'; ?>



 