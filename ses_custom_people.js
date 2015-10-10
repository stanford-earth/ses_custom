(function ($)
{
    $(document).ready(function() {

        $('#edit-field-ses-workgroup-membership-value-wrapper').hide();
        $('.view-id-people >> .views-summary').addClass('btn');
        //$('.view-display-id-glossary >> .views-summary').addClass('btn');
        var subUrl = '';
        var subSize = 0;
        var glossary_name = '';
        var allStr = -1;
        var glossaries = [
        {
            'name':'.view-display-id-eiper_all_glossary',
            'surl':'eiper/people/all'
        },
        {
            'name':'.view-display-id-esys_all_glossary',
            'surl':'esys-d7/people/all'
        },
        {
            'name':'.view-display-id-glossary',
            'surl':'people/all'
        },
        {
            'name':'.view-display-id-esys_faculty_glossary',
            'surl':'esys-d7/people/faculty'
        },
        {
            'name':'.view-display-id-eiper_glossary',
            'surl':'eiper/people/faculty'
        },
        {
            'name':'.view-display-id-eiper_phd_glossary',
            'surl':'eiper/people/students-phd'
        },
        {
            'name':'.view-display-id-eiper_ms_glossary',
            'surl':'eiper/people/students-ms'
        },
        {
            'name':'.view-display-id-eiper_alumni_phd_glossary',
            'surl':'eiper/people/alumni-phd'
        },
        {
            'name':'.view-display-id-eiper_alumni_ms_glossary',
            'surl':'eiper/people/alumni-ms'
        }];
        for (i=0; i<glossaries.length; i++) {
            allStr = document.URL.indexOf(glossaries[i].surl);
            if (allStr > -1) {
                subSize = glossaries[i].surl.length;
                subUrl = document.URL.substr(0,allStr+subSize);
                glossary_name = glossaries[i].name;
                break;
            }
        }
        if (subSize > 0) {
            var newUrl = '<div class="views-summary views-summary-unformatted btn"><a href="'+subUrl+'">*</a></div>';
            $(glossary_name+' > .view-content').prepend(newUrl);
        }

        $("#edit-name").blur(function(event) {
            if ($(this).val() != 0) {
                $("#edit-field-ses-associate-type-tid-1").val("All");
                $("#edit-field-secondary-affiliations-value").val("All");
            }        
        });

        $("#edit-field-ses-associate-type-tid-1").blur(function(event) {
            if ($(this).val() != 'All') {
                $("#edit-name").val('');
            }
        });

        $("#edit-field-secondary-affiliations-value").blur(function(event) {
            if ($(this).val() != 'All') {
                $("#edit-name").val('');
            }
        });

    });


}(jQuery));
