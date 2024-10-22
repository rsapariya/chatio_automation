<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload and Modify Image</title>
    <script
  src="https://code.jquery.com/jquery-3.7.1.min.js"
  integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo="
  crossorigin="anonymous"></script>
</head>
<body>
    <form method="post" id="gd_frm" enctype="multipart/form-data">
        <input type="text" name="text" maxlength="10"/><br/><br/>
        <input type="file" name="imageInput" accept="image/*">
        <button type="button" id="upload">Upload</button>
    </form>
    <div class="canvas">
        
    </div>
</body>
<script>
    jQuery(document).on('click', "#upload", function (event) {
        event.preventDefault();
        var data = new FormData($(document).find("#gd_frm")[0]);
        let imgCanvas = document.querySelector('.canvas');
        imgCanvas.innerHTML = '';
        jQuery.ajax({
            type: "POST",
            url: "<?php echo base_url() ?>api/modify_img",
            data: data,
            async: true,
            contentType: false,
            processData: false,
            success: function (response) {
               let json = JSON.parse(response);
               if(!json.status){
                   imgCanvas.innerHTML ='<p>'+json.error+'</p>';
               }else{
                   imgCanvas.innerHTML = '<img src="'+json.url+'" >';
               }
            }
        });
    });
</script>
</html>