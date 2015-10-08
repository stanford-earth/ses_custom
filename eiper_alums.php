<?php

/**
 * @file
 * The PHP page that serves all page requests on a Drupal installation.
 *
 * The routines here dispatch control to the appropriate handler, which then
 * prints the appropriate page.
 *
 * All Drupal code is released under the GNU General Public License.
 * See COPYRIGHT.txt and LICENSE.txt.
 */

/**
 * Root directory of Drupal installation.
 */
define('DRUPAL_ROOT', getcwd());

require_once DRUPAL_ROOT . '/includes/bootstrap.inc';
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);

$sql = "select u.uid from users u, field_data_field_alumnus a, field_data_field_ses_associate_type g where u.uid = a.entity_id and u.uid = g.entity_id and a.field_alumnus_value = 1 and g.field_ses_associate_type_tid = :tid";
for ($i = 2165; $i<2167; $i++) {
    $wg = 'earthsci:eiper-students-graduate';
    if ($i == 2165) $wg .= '-phd';
    $result = db_query($sql, array(':tid' => $i));
    foreach ($result as $row) {
        $account = user_load($row->uid);
        $wg_array = array();
        //if (isset($account->field_ses_workgroup_membership) &&
        //    !empty($account->field_ses_workgroup_membership[LANGUAGE_NONE])) {
        //    $wg_array = $account->field_ses_workgroup_membership[LANGUAGE_NONE];
        //}
        $edit = array('field_ses_workgroup_membership' => array('und' => array(array('value' => $wg))));
        if ($i == 2165) $edit['field_ses_phd_student'] = array('und' => array(array('value' => '1')));
        user_save($account, $edit);
        $count += 1;
    }
    print 'count: ' . $count . '<br />';
}
    $account = user_load(3038);
    print '<pre>'; print_r($account);print '</pre>';

