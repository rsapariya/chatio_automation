<?php
$text = '';
$message = '';
if ($this->session->flashdata('error_msg')) {
    $text = 'Error!';
    $message = $this->session->flashdata('error_msg');
} elseif ($this->session->flashdata('success_msg')) {
    $text = 'Success!';
    $message = $this->session->flashdata('success_msg');
}
if ($text != '' && $message != '') {
    ?>
    <div class="alert alert-gradient mb-4 alert-message" role="alert"> 
        <i class="flaticon-cancel-12 close" data-dismiss="alert"></i> 
        <strong><?php echo $text; ?></strong> <?php echo $message ?>
    </div>
<?php }
?>
<script>
    $(".alert-message").delay(3000).fadeOut(300);
</script>
