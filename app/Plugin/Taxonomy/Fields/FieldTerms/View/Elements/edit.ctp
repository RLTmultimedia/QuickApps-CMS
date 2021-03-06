<?php
    $field['FieldData'] = !isset($field['FieldData']) ? array() : $field['FieldData'];
    $field['FieldData'] = array_merge(array('id' => null, 'field_id' => null, 'foreignKey' => null, 'belongsTo' => null, 'data' => ''), $field['FieldData']);
    $options = array();

    $field['settings'] = array_merge(
        array(
            'vocabulary' => 0,
            'type' => 'checkbox',
            'max_values' => 0
        ),
        $field['settings']
    );

    if ($field['settings']['vocabulary'] > 0) {
        $options = ClassRegistry::init('Taxonomy.Term')->generateTreeList(
            array(
                'Term.vocabulary_id' => $field['settings']['vocabulary']
            ), null, null, '&nbsp;&nbsp;&nbsp;|-&nbsp;'
        );
    }

    if (isset($this->data['FieldData']['FieldTerms'][$field['id']]['data'])) {
        $selected = $this->data['FieldData']['FieldTerms'][$field['id']]['data'];
    } else {
        $selected = explode('|', (string)$field['FieldData']['data']);
    }

    // max_values > 1
    $Options = array(
        'escape' => false,
        'type' => 'select',
        'label' => $field['label'],
        'multiple' => ($field['settings']['type'] === 'checkbox' ? 'checkbox' : true),
        'options' => $options,
        'value' => $selected
    );

    if ($field['settings']['type'] == 'select' && $field['settings']['max_values'] == 1) {
        $Options['multiple'] = false;
    } elseif ($field['settings']['type'] == 'checkbox' && $field['settings']['max_values'] == 1) {
        $Options['type'] = 'radio';
        $Options['separator'] = '<br />';
        $Options['legend'] = $field['label'];
        $Options['value'] = @$selected[0];
        unset($Options['multiple']);
    }

    echo $this->Form->input("FieldData.FieldTerms.{$field['id']}.data", $Options);
    echo $this->Form->hidden("FieldData.FieldTerms.{$field['id']}.id", array('value' => $field['FieldData']['id']));
?>

<?php if (!empty($field['description'])): ?>
    <em><?php echo $field['description']; ?></em>
<?php endif; ?>