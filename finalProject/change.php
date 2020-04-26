<?php
session_start();
include 'connect.php';

// time to update tables, so check for required fields
if (($_POST['f_name']== "" )|| ($_POST['l_name']== "" )) {
    header("Location: changeEntry.php");
    exit;
}
//connect to database
doDB();
//create clean version of input strings
$master_id=$_SESSION["id"];
$safe_f_name = mysqli_real_escape_string($mysqli, $_POST['f_name']);
$safe_l_name = mysqli_real_escape_string($mysqli, $_POST['l_name']);
$safe_address = mysqli_real_escape_string($mysqli, $_POST['address']);
$safe_city = mysqli_real_escape_string($mysqli, $_POST['city']);
$safe_state = mysqli_real_escape_string($mysqli, $_POST['state']);
$safe_zipcode = mysqli_real_escape_string($mysqli, $_POST['zipcode']);
$safe_tel_number = mysqli_real_escape_string($mysqli, $_POST['tel_number']);
$safe_fax_number = mysqli_real_escape_string($mysqli, $_POST['fax_number']);
$safe_email = mysqli_real_escape_string($mysqli, $_POST['email']);
$safe_note = mysqli_real_escape_string($mysqli, $_POST['note']);

// update master_name table
$add_master_sql ="UPDATE master_name SET
date_added=now(), date_modified=now(),f_name='".$safe_f_name."',l_name='".
$safe_l_name."'".
"WHERE id=" .$master_id;
$add_master_res = mysqli_query($mysqli,$add_master_sql) or 
die(mysqli_error($mysquli));

    if ($_SESSION["address"]=="true") {
    // update address table
       $add_master_sql= "UPDATE address SET
       master_id=".master_id.",date_added=now(),date_modified=now()".
       ",address='".$safe_address."', city='".$safe_city ."',state='".
       $safe_state. 
        "', zipcode='". $safe_zipcode ."', type='".$_POST['add_type']."'".      
        "WHERE master_id=".$master_id;  
        $add_address_res = mysqli_query($mysqli, $add_address_sql) or 
        die(mysqli_error($mysqli));  
    } 
    else if (($_POST['address']) || ($_POST['city']) || ($_POST['state']) || 
        ($_POST['zipcode'])) {  
    //add new record to table   
        $add_address_sql = "INSERT INTO address (master_id, date_added, date_modified,
        address, city, state, zipcode, type)  VALUES ('".   
        $master_id."',now(), now(), '".$safe_address."', '".$safe_city.        
        "','".$safe_state."' , '".$safe_zipcode."' , 
        '".$_POST['add_type']."')"; 
        $add_address_res = mysqli_query($mysqli, $add_address_sql) or
        die(mysqli_error($mysqli));
    }
    if ($_SESSION["telephone"]=="true"){   
    //update telephone table   
        $add_tel_sql = "UPDATE telephone SET master_id=".$master_id.",
        date_added=now(),date_modified=now()".
        ",tel_number='".$safe_tel_number."',type='".$_POST['tel_type']."'".                
        "WHERE master_id=".$master_id;   
        $add_tel_res = mysqli_query($mysqli, $add_tel_sql) or 
        die(mysqli_error($mysqli));
    } 
    else if ($_POST['tel_number']){  
    // add new record to telephone table  
        $add_tel_sql = "INSERT INTO telephone (master_id, date_added, date_modified,          
        tel_number, type)  VALUES ('".$master_id."', now(), now(),                 
        '".$safe_tel_number."', '".$_POST['tel_type']."')";   
        $add_tel_res = mysqli_query($mysqli, $add_tel_sql) or die(mysqli_error($mysqli));  
    }
    if ($_SESSION["fax"]=="true"){   
    //update fax table    
        $add_fax_sql = "UPDATE  fax SET master_id=".$master_id.",
        date_added=now(),date_modified=now()".      
        ",fax_number='".$safe_fax_number ."', type='".
        $_POST['fax_type']."'".      
        "WHERE master_id=".$master_id;  
        $add_fax_res = mysqli_query($mysqli, $add_fax_sql) or die(mysqli_error($mysqli));    
    } 
        else if ($_POST['fax_number']) {   
    // add new record to fax table  
        $add_fax_sql = "INSERT INTO fax (master_id, date_added, date_modified,                
        fax_number, type)  VALUES ('".$master_id."', now(), now(),               
        '".$safe_fax_number."', '".$_POST['fax_type']."')";    
        $add_fax_res = mysqli_query($mysqli, $add_fax_sql) or die(mysqli_error($mysqli));  
    }
    if ($_SESSION["email"]=="true"){   
    //update email table  
        $add_email_sql = "UPDATE  email  SET master_id=".$master_id.", 
        date_added=now(),date_modified=now()".                    
        ",email='".$safe_email."',type='".$_POST['email_type']."'".       
        "WHERE master_id=".$master_id;    
        $add_email_res = mysqli_query($mysqli, $add_email_sql) or die(mysqli_error($mysqli));  
    }
        else if ($_POST['email']){ 
    // add new record to email table    
        $add_email_sql = "INSERT INTO email (master_id, date_added, date_modified,            
        email, type)  VALUES ('".$master_id."', now(), now(),       
        '".$safe_email."', '".$_POST['email_type']."')";    
        $add_email_res = mysqli_query($mysqli, $add_email_sql) or 
        die(mysqli_error($mysqli)); 
    }
    if ($_SESSION["notes"]=="true"){    
    //update notes table    
        $add_notes_sql = "UPDATE personal_notes SET master_id=".$master_id.", date_added=now(),date_modified=now()".                      
        ",note='".$safe_note."'".                      
        "WHERE master_id=".$master_id;    
        $add_notes_res = mysqli_query($mysqli, $add_notes_sql) or 
        die(mysqli_error($mysqli));  
    } 
    else  if ($_POST['note']) {    
    // add new record to notes table    
        $add_notes_sql = "INSERT INTO personal_notes (master_id, date_added, sdate_modified,
        note)  VALUES ('".$master_id."', now(), now(), '".$safe_note."')";    
        $add_notes_res = mysqli_query($mysqli, $add_notes_sql) or 
        die(mysqli_error($mysqli)); 
    }
    mysqli_close($mysqli);  
    $display_block = "<p>Your entry has been changed...</p>";

?> 

<?php include 'BeginNav.php'; ?> 
<?php echo $display_block; ?> 
<?php include 'EndNav.php'; ?>



