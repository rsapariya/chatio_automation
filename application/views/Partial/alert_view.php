<style>
    .alert-light-danger p{ color: #e7515a !important; }
</style>
<?php
$text = '';
$message = '';
//$text_alert = 'alert-gradient';
if ($this->session->flashdata('error_msg')) {
    $text = 'Error!';
    $message = $this->session->flashdata('error_msg');
    $text_alert = 'alert-light-danger';
} elseif ($this->session->flashdata('success_msg')) {
    $text = 'Success!';
    $message = $this->session->flashdata('success_msg');
    $text_alert = 'alert-light-success';
}
if ($text != '' && $message != '') {
    ?>

<div class="alert <?php echo $text_alert ?> alert-dismissible fade show mb-4 alert-message" role="alert"> 
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" data-bs-dismiss="alert" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x close"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
    </button>
    <strong class="text-white-"><?php echo $text; ?></strong> <?php echo $message ?>
</div>
<?php }
?>
<script>
    $(".alert-message").delay(3000).fadeOut(300);
</script>
