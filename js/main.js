$(function() {
 
    /* 
    * SignUp button event handler
    * @login.php
    */     
     $("button#signUp").on("click", function(event){
      window.location= "signUp.php"; 
      return false;     
   }); 
   
    /* 
    * Logout button event handler
    * 
    */     
     $("button#logout").on("click", function(event){
      var sid = $(this).attr("data-sid");
      
      //if cookies are disabled pass session id (SID) through the URL
      if(sid) {
          window.location= "logout.php?PHPSESSID=" + sid; 
      } else {
          window.location= "logout.php"; 
      }         
      window.location= "logout.php"; 
      return false;     
   });     
    
   /* 
    * Edit button event handler
    * @memberEdit.php
    */     
     $("table tbody").on("click","button.edit", function(event){
      var id = $(this).parents("tr").attr("id"),
          sid = $(this).parents("td").attr("data-sid");
         
      //if cookies are disabled pass session id (SID) through the URL
      if(sid) {
          window.location= "memberEdit.php?id=" + id + "&PHPSESSID=" + sid; 
      } else {
          window.location= "memberEdit.php?id=" + id; 
      }
      return false;     
   });  
   
   /*
     * Home button click event handler 
     */
    $("button#home").click(function() { 
      var sid = $(this).attr("data-sid");

      //if cookies are disabled pass session id (SID) through the URL
      if(sid) {
          window.location= './?' + sid; 
      } else {
          window.location= './'; 
      }                 
      return false;
    }); 
    
   //Submit error function for response code error messages
   function submitError(response, textStatus) { 
       if(response.status===0){
            alert('You are offline!!\n Please Check Your Network.');
        }else if(response.status===401){
            alert('Unauthorized access. Please provide correct username and password');
        }else if(response.status===404){
            alert('Requested URL not found.');
        }else if(response.status===500){
            alert('Internel Server Error.');
        }else if(textStatus ==='parsererror'){
            alert('Error.\nParsing JSON Request failed.');
        }else if(textStatus ==='timeout'){
            alert('Request Time out.');
        }else {
            alert('Unknow Error.\n'+ response.responseText);
        }      
  }
          
   /* 
    * Delete button event handler
    * Ajax submit @memberDelete.php
    */ 
   $("table tbody").on("click", "button.delete", function(event){
      var member = $(this).parents("tr"),
          id = member.attr("id"),
          name = member.attr("data-name"),
          iconFullPath = member.find("td.icon img").attr("src"),
          sid = $(this).parents("td").attr("data-sid"),
          deleteURL;
            
      if (confirm('Are you sure you want to delete member ' + name + '?')) {
          
          //if cookies are disabled pass session id (SID) through the URL
          if(sid) {
              deleteURL= "memberDelete.php?PHPSESSID=" + sid; 
          } else {
              deleteURL= "memberDelete.php"; 
          }    
          $.ajax({
            type: "POST",
            url: deleteURL,
            dataType: "text",
            data: { id: id, iconFullPath: iconFullPath },
            error: submitError
            }).done(function(msg) {
                //alert (msg);
                location.reload(); 
                }); 
           }  
           return false; 
   });
 
  /*
   *  Add new member ajax form submission success handler
   *  Adds new table row with new submission data @index.php
   */

  function submitSuccess(data) { 
    if(data.error === "true"){
        $('#status').addClass("error").removeClass("alert").html(data.message);
        //alert(data.message);
    } else{
        var tr;
        
        tr = $("<tr id='" + data.id + "' data-name='" + data.firstName + " " + data.lastName +  "'>");
        tr.append("<td class='icon'><img src='" + data.iconFullPath + "' alt='Profile Image' /></td>");
        tr.append("<td>" + data.firstName + "</td>");
        tr.append("<td>" + data.lastName + "</td>");
        tr.append("<td>" + data.email + "</td>");
        tr.append("<td>" + data.phoneNumber + "</td>");
        tr.append("<td>" + data.message + "</td>");
        tr.append("<td><button class='btn btn-primary edit' type='button'>Edit</button>&nbsp;&nbsp;&nbsp;<button class='btn btn-danger delete' type='button'>Delete</button></td>");
        $('tbody#members').prepend(tr);
        
        $('label.error').css('background','url("")');
        $('#status').removeClass("error alert").html("");
        $('form').clearForm();  //clear all form fields after successful submit 
     }    
  }

 /*
  * signUp form validation
  * submitHandler ajax submit @signUp.php
  */
  $('#signUp').validate(
    {
        rules: {
            username: {
            minlength: 2,
            required: true
            },
            password1: {
            minlength: 2,
            required: true
            },
            password2: {
            minlength: 2,
            equalTo: "#password1",
            required: true
            },
            firstName: {
            minlength: 2,
            required: true
            },  
            lastName: {
            minlength: 2,
            required: true
            },        
            email: {
            required: true,
            email: true
            },
            phoneNumber: {
            minlength: 10,
            required: true
            },
            streetAddress: {
            required: true
            },
            city: {
            required: true
            },
            state: {
            required: true
            },
            zip: {
            required: true
            },
            age: {
            required: true
            },
            position: {
            required: true
            },
            icon: {
            required: true
            }
        }, 
        highlight: function(element) {
            $(element).closest('.control-group').removeClass('success').addClass('error');
        },
        success: function(element) {
            $(element).text('OK!').addClass('valid').closest('.control-group').removeClass('error').addClass('success');
        }
   });  
    
 /*
  * signUpEdit form validation
  * submitHandler ajax submit @memberEdit.php
  */
  $('#signUpEdit').validate(
    {
        rules: {
            firstName: {
            minlength: 2,
            required: true
            },
            lastName: {
            minlength: 2,
            required: true
            },        
            email: {
            required: true,
            email: true
            },
            phoneNumber: {
            minlength: 10,
            required: true
            },
            streetAddress: {
            required: true
            },
            city: {
            required: true
            },
            state: {
            required: true
            },
            zip: {
            required: true
            },
            age: {
            required: true
            },
            position: {
            required: true
            }
        },
        highlight: function(element) {
            $(element).closest('.control-group').removeClass('success').addClass('error');
        },
        success: function(element) {
            $(element).text('OK!').addClass('valid').closest('.control-group').removeClass('error').addClass('success');
        }
    });  
    
  
});