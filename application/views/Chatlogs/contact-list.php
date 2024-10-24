<?php
if (isset($contacts) && !empty($contacts)) {
    $i = isset($offset) && !empty($offset) ? $offset : 0;
    foreach ($contacts as $cont) {
        $name = isset($cont['name']) && !empty($cont['name']) ? $cont['name'] : $cont['from_profile_name'];
        $is_contact = isset($cont['name']) && !empty($cont['name']) ? 'yes' : 'no';
        ?>
        <div id="<?php echo '#contact-'.($i+=1) ?>" class="person <?php echo isset($active_contact) && $active_contact == $cont['phone_number'] ? 'active' : '' ?>"  data-contact="<?php echo!empty($cont['phone_number']) ? $cont['phone_number'] : '' ?>">
            <div class="user-info">
                <div class="f-head">
                </div>
                <div class="f-body">
                    <div class="meta-info">
                        <span class="user-name" data-name="<?php echo !empty($name) ? $name : '' ?>"  data-number="<?php echo $cont['phone_number'] ?>" data-is_contact="<?php echo $is_contact ?>"><?php echo !empty($name) ? $name : $cont['phone_number'] ?></span>
                        <span class="user-meta-time"><?php echo $cont['created'] ?></span>
                    </div>
                    <?php if (!empty($cont['message_type']) && ($cont['message_type'] == 'text' || $cont['message_type'] == 'button_reply') && !empty($cont['message'])) { ?>
                        <span class="preview"><?php echo strlen($cont['message']) > 25 ? substr($cont['message'], 0, 25) . '...' : $cont['message'] ?></span>
                    <?php } else if (!empty($cont['message_type']) && ($cont['message_type'] == 'video')) { ?>
                        <span class="preview"><i class="fa fa-file-video text-muted"></i> Video</span>
                     <?php } else if (!empty($cont['message_type']) && ($cont['message_type'] == 'audio')) { ?>
                        <span class="preview"><i class="fa fa-file-audio text-muted"></i> Audio</span>
                    <?php } else if (!empty($cont['message_type']) && ($cont['message_type'] == 'image')) { ?>
                        <span class="preview"><i class="fa fa-file-image text-muted"></i> Image</span>
                    <?php } else if (!empty($cont['message_type']) && ($cont['message_type'] == 'document')) { ?>
                        <span class="preview"><i class="fa fa-file text-muted"></i> Document</span>
                    <?php } else if (!empty($cont['message_type']) && ($cont['message_type'] == 'template')) { ?>
                        <span class="preview"><i class="fa fa-file-lines text-muted"></i> Template</span>
                    <?php } 
                    if (!empty($cont['unread_message'])) { ?>
                        <span class="counter"><?php echo $cont['unread_message'] ?></span>
                    <?php } ?>
                </div>
            </div>
        </div>
        <?php
    }
} else {
    ?>
    <div class="" data-chat=""><div class="user-info"><div class="f-body"><div class="meta-info text-center"><span>Data not fount!</span></div></div></div></div>
    <?php
}?>