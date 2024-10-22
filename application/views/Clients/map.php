<input name="file_name" type="hidden" value="<?php echo isset($file_name) && !empty($file_name) ? $file_name : '' ?>" >
<?php
$fields = [];
if (isset($sheetsdata) && !empty($sheetsdata)) {

    $columns_name = $sheetsdata['columns'];
    $index = $sheetsdata['index'];
    $values = $sheetsdata['rows'];
    $mandatoryField = array('name', 'phone_number');
    $optionalField = array('email', 'birth_date', 'anniversary_date');
    $dynamicFields = array('column1', 'column2', 'column3', 'column4', 'column5', 'column6', 'column7', 'column8', 'column9', 'column10');

    $fields = array_merge($mandatoryField, $optionalField, $dynamicFields);
    ?>
    <input type="hidden" id="total_columns" value="<?php echo count($columns_name); ?>" />
    <table class="table table-striped dataTable table-bordered">
        <thead>
            <tr>
                <?php foreach ($columns_name as $cm) { ?>
                <th><?php 
                    if(!empty($cm)){
                        echo strtoupper(str_replace('_', ' ', $cm)); 
                    }
                    ?></th>
                <?php }
                ?>
            </tr>
            <tr>
                <?php
                $count = 1;
                foreach ($index as $is) {
                    ?>
                    <td>
                        <select name="<?php echo $is ?>" id="<?php echo $is . '-column' ?>" class="form-control mapping">
                            <?php if (!empty($fields)) { ?>
                                <option selected="selected" disabled="disabled"> select </option>
                                <?php foreach ($fields as $field) {
                                    ?>
                                    <option value="<?php echo $field ?>"> <?php echo strtoupper(str_replace('_', ' ', $field)); ?> </option>
                                    <?php
                                }
                            }
                            ?>
                        </select></td>
                <?php } ?>
            </tr>
        </thead>
        <tbody>
            <?php
            if (!empty($values)) {
                $count = 1;
                foreach ($values as $v) {
                    if ($count <= 7) {
                        ?>
                        <tr>
                            <?php
                            if (!empty($index)) {
                                foreach ($index as $i) {
                                    ?>
                                    <td><?php echo $v[$i] ?></td>
                                    <?php
                                }
                            }
                            ?>
                        </tr>
                        <?php
                    }
                    $count++;
                }
            }
            ?>
        </tbody>
    </table>
<?php } ?>