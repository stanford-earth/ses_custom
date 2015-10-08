<?php

// return the vocabulary id number for a taxonomy vocabulary machine name
function _ses_custom_get_personnel_vid($machine_name = '') {
    $vid = FALSE;
    $vocabs = taxonomy_get_vocabularies();
    foreach ($vocabs as $vocabulary) {
        if (isset($vocabulary->machine_name) && $vocabulary->machine_name === $machine_name) {
            $vid = $vocabulary->vid;
            break;
        }
    }
    return $vid;
}