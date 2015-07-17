(function ($)
{
    $(document).ready(function() {
        $('.sensory-earth-list-item').each(function(index){
            var fade = $(".views-field-field-sensory-earth-fade-image img:first", this).attr("src");
            //console.log(fade);
            //console.log($(".views-field-field-sensory-earth-main-image", this).html());
            $(".views-field-field-sensory-earth-main-image", this).css("background-image","url("+fade+")");
        });  

        $( ".views-field-field-sensory-earth-main-image a" ).hover(
            function() {
                $(this).find("img").stop().animate({ opacity : 0 }, 400);
                //$(this).find("img").stop(true,true).fadeOut();
            },
            function() {
                //$(this).find("img").stop(true,true).fadeIn();
                $(this).find("img").stop().animate({ opacity : 1 }, 400);
            }
        );
    });
}(jQuery));
