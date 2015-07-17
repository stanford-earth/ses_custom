(function ($)
{
    $(document).ready(function() {

//        console.log(Drupal.settings);
//        console.log(Drupal.settings.allowAdminTab);
//        console.log(Drupal.settings.allowAdditionalTab);

        if (!Drupal.settings.allowAdminTab) {
            $('.group-account-main li:last').prev().hide();
        }
        if (!Drupal.settings.allowDepartmentTab) {
            $('.group-account-main li:last').hide();
        }

        if (!Drupal.settings.hideCapFields) {
            $('.profile-cap-fields').removeClass('hidden-profile-section');
        }

        $('#edit-field-cap-profile-image-und-0-remove-button').hide();
        $('#edit-field-ses-research-group .hierarchical-select').hide();
        $('#edit-field-ses-research-group .dropbox-remove').hide();
    });

    function _ses_set_secondary_checkboxes(selVal) {
        // find the secondary affil checkbox that matches the value of the primary affil
        var findVal = 'input[name="field_secondary_affiliations[und][' + selVal + ']"]';
        var fieldName = '#edit-field-secondary-affiliations-und';
 
        // check the matching secondary affil checkbox, enable all checkboxes and disable matching
        $(fieldName).find(findVal).prop('checked','checked');
        $(fieldName).find('input[type=checkbox]:disabled').prop('disabled',false);
        $(fieldName).find(findVal).prop('disabled',true);
    }

}(jQuery));
