function ValidateFileUpload() {
        var fuData = document.getElementById('fileChooser');
        var FileUploadPath = fuData.value;

//To check if user upload any file
        /*if (FileUploadPath == '') {
            alert("Please upload an image");

        } else {*/
            var Extension = FileUploadPath.substring(
                    FileUploadPath.lastIndexOf('.') + 1).toLowerCase();

//The file uploaded is an image

if (Extension == "png" || Extension == "jpeg" || Extension == "jpg") {

// To Display
                if (fuData.files && fuData.files[0]) {
                    var reader = new FileReader();

                    reader.onload = function(e) {
                        $('#blah').attr('src', e.target.result);
                    }

                    reader.readAsDataURL(fuData.files[0]);
                }

            } 

//The file upload is NOT an image
else {  
            document.getElementById("image-error").innerHTML = "Photo only allows file types of PNG, JPG, JPEG.";
            }
        /*}*/
    }

$(document).ready(function () {
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
 
    $(".login-input").submit(function(e) {
        e.preventDefault();
        var mobile, name, email, dob, image, password, confirmPwd;
        mobile = $('input[name="mobile"]').val();
        name = $('input[name="user_name"]').val();
        email = $('input[name="email"]').val();
        dob = $('input[name="dob"]').val();
        image = $('input[name="image"]').val();
        password = $('input[name="password"]').val();
        confirmPwd = $('input[name="confirm-password"]').val();
        if(image != ""){
            var imgresponse = ValidateFileUpload(image);
        }
        if(password != confirmPwd){
            $("#password-error").text('Password do not match.');
        }else{
            jQuery.ajax({
                type: 'POST',
                url:$('input[name="url"]').val(),
                processData: false,
                contentType: false,
                data: new FormData(this),
                success: function (data) {
                    console.log(data);
                    if(data == "The phone number has already been taken."){
                        jQuery(".mobile-error").html("The phone number has already been taken.");
                    }else if(data == "Register successfully."){
                        window.location.href=$('input[name="index-url"]').val();
                    }else if(data == "The password must be at least 8 characters."){
                        jQuery("#password-error").html("The password must be at least 8 characters.");
                    }else if(data == "The mobile must be at least 10 characters."){
                        jQuery(".mobile-error").html("The password must be at least 8 characters.");
                    }
                }
              });
        }
    });
});